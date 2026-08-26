<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\AuditLogger;
use App\Services\ProductService;
use App\Services\SectorService;
use App\Traits\HandlesImageUploads;
use App\Traits\PaginatesQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    use HandlesImageUploads, PaginatesQuery;

    protected ProductService $products;

    protected SectorService $sectors;

    /** Number of products to display per admin list page */
    private const PRODUCTS_PER_PAGE = 15;

    /** Maximum gallery images stored per product */
    private const MAX_GALLERY_IMAGES = 10;

    /** Maximum products accepted in one bulk submit */
    private const MAX_BULK_PRODUCTS = 50;

    public function __construct(ProductService $products, SectorService $sectors)
    {
        $this->products = $products;
        $this->sectors = $sectors;
    }

    public function productsIndex(Request $request)
    {
        $query = Product::query();

        $search = $request->input('s');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('catalog', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $category = $request->input('category');
        if ($category) {
            $query->where('category', $category);
        }

        $sector = $request->input('sector');
        if ($sector) {
            if (DB::connection()->getDriverName() === 'sqlite') {
                $query->whereRaw("',' || sector || ',' LIKE ?", ["%,{$sector},%"]);
            } else {
                $query->where(function ($q) use ($sector) {
                    $q->where('sector', $sector)
                        ->orWhereRaw('FIND_IN_SET(?, sector)', [$sector]);
                });
            }
        }

        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $sort = $request->input('sort', 'newest');
        match ($sort) {
            'oldest'    => $query->orderBy('created_at', 'asc')->orderBy('id', 'asc'),
            'name_asc'  => $query->orderBy('title', 'asc'),
            'name_desc' => $query->orderBy('title', 'desc'),
            default     => $query->orderBy('created_at', 'desc')->orderBy('id', 'desc'),
        };

        ['items' => $products, 'currentPage' => $currentPage, 'totalPages' => $totalPages]
            = $this->paginateQuery($query, $request, self::PRODUCTS_PER_PAGE);

        $sectors = $this->sectors->getSectors();
        $categories = ProductCategory::whereNull('parent_id')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'key']);

        return view('admin.products.index', [
            'products'    => $products,
            'sectors'     => $sectors,
            'categories'  => $categories,
            'search'      => $search,
            'category'    => $category,
            'sector'      => $sector,
            'sort'        => $sort,
            'start_date'  => $startDate,
            'end_date'    => $endDate,
            'currentPage' => $currentPage,
            'totalPages'  => $totalPages,
        ]);
    }

    public function productsCreate()
    {
        $sectors    = $this->sectors->getSectors();
        $categories = ProductCategory::whereNull('parent_id')
            ->orderBy('sort_order')->orderBy('name')->get();

        $product = [
            'title'          => '',
            'catalog'        => '',
            'category'       => '',
            'sub_category'   => '',
            'sector'         => '',
            'image'          => '',
            'gallery_images' => [],
            'description'    => '',
        ];

        return view('admin.products.form', compact('sectors', 'product', 'categories'));
    }

    public function productsStore(StoreProductRequest $request)
    {
        $title = $request->input('title');

        if ($this->products->getProductByTitle($title)) {
            return redirect()->back()->withInput()->with('error', 'Produk dengan judul tersebut sudah ada.');
        }

        $image         = $this->handleImageUpload($request, 'image_file', 'image_url', '/images/placeholder.svg');
        $galleryImages = $this->handleMultipleImageUploads($request, 'gallery_files');

        $product = [
            'catalog'        => $request->input('catalog') ?: '',
            'title'          => $title,
            'description'    => $request->input('description') ?: '',
            'category'       => $request->input('category'),
            'sub_category'   => $request->input('sub_category') ?: '',
            'sector'         => $request->input('sector') ?: '',
            'image'          => $image,
            'gallery_images' => $galleryImages,
            'price'          => (float) $request->input('price', 0),
            'stock'          => (int) $request->input('stock', 0),
        ];

        $createdProduct = $this->products->addProduct($product);

        AuditLogger::log('product.create', 'Product', $createdProduct['id'] ?? null, [
            'title'   => $title,
            'catalog' => $product['catalog'],
            'price'   => $product['price'],
        ]);

        return redirect()->route('admin.products')->with('success', 'Produk baru berhasil ditambahkan!');
    }

    public function productsEdit(int $id)
    {
        $product = $this->products->getProductById($id);
        if (! $product) {
            return redirect()->route('admin.products')->with('error', 'Produk tidak ditemukan.');
        }
        $sectors    = $this->sectors->getSectors();
        $categories = ProductCategory::whereNull('parent_id')
            ->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.products.form', compact('product', 'sectors', 'categories'));
    }

    public function productsUpdate(UpdateProductRequest $request, int $id)
    {
        $product = $this->products->getProductById($id);
        if (! $product) {
            return redirect()->route('admin.products')->with('error', 'Produk tidak ditemukan.');
        }

        $newTitle = $request->input('title');
        $existing = $this->products->getProductByTitle($newTitle);

        // getProductByTitle may return Model or array-like
        $existingId = is_object($existing) ? (int) ($existing->id ?? 0) : (int) ($existing['id'] ?? 0);
        if ($existing && $existingId !== $id) {
            return redirect()->back()->withInput()->with('error', 'Produk dengan judul baru tersebut sudah ada.');
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', $product['image'] ?? $product->image ?? null);

        $existingGallery = $product['gallery_images'] ?? $product->gallery_images ?? [];
        if (! is_array($existingGallery)) {
            $existingGallery = [];
        }
        $toRemove = (array) $request->input('remove_gallery', []);
        if (! empty($toRemove)) {
            $existingGallery = array_values(array_diff($existingGallery, $toRemove));
        }
        $newGalleryImages = $this->handleMultipleImageUploads($request, 'gallery_files');
        $galleryImages    = array_values(
            array_slice(array_merge($existingGallery, $newGalleryImages), 0, self::MAX_GALLERY_IMAGES)
        );

        $updatedProduct = [
            'catalog'        => $request->input('catalog') ?: '',
            'title'          => $newTitle,
            'description'    => $request->input('description') ?: '',
            'category'       => $request->input('category'),
            'sub_category'   => $request->input('sub_category') ?: '',
            'sector'         => $request->input('sector') ?: '',
            'image'          => $image,
            'gallery_images' => $galleryImages,
            'price'          => (float) $request->input('price', 0),
            'stock'          => (int) $request->input('stock', 0),
        ];

        $this->products->updateProductById($id, $updatedProduct);

        AuditLogger::log('product.update', 'Product', $id, [
            'title'   => $newTitle,
            'catalog' => $updatedProduct['catalog'],
            'price'   => $updatedProduct['price'],
        ]);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diperbarui!');
    }

    public function productsDestroy(int $id)
    {
        $product = $this->products->getProductById($id);
        $this->products->deleteProductById($id);

        AuditLogger::log('product.delete', 'Product', $id, [
            'title'   => is_object($product) ? ($product->title ?? null) : ($product['title'] ?? null),
            'catalog' => is_object($product) ? ($product->catalog ?? null) : ($product['catalog'] ?? null),
        ]);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus!');
    }

    public function productsCreateBulk()
    {
        $sectors             = $this->sectors->getSectors();
        $categoriesStructure = $this->products->getCategoriesStructure();

        return view('admin.products.bulk-form', compact('sectors', 'categoriesStructure'));
    }

    public function productsStoreBulk(Request $request)
    {
        $titles = $request->input('title', []);

        if (! is_array($titles) || count($titles) === 0) {
            return redirect()->back()->with('error', 'Tidak ada data produk yang dikirim.');
        }

        if (count($titles) > self::MAX_BULK_PRODUCTS) {
            return redirect()->back()->with('error', 'Maksimal '.self::MAX_BULK_PRODUCTS.' produk per sekali submit.');
        }

        // Only accept known category keys (parents + children)
        $allowedCategoryKeys = ProductCategory::query()->pluck('key')->filter()->all();
        $allowedCategoryKeys = array_fill_keys($allowedCategoryKeys, true);

        $productsToStore = [];
        $skipped = 0;

        foreach ($titles as $rowKey => $title) {
            if (count($productsToStore) >= self::MAX_BULK_PRODUCTS) {
                break;
            }

            $title    = Str::limit(trim((string) $title), 255, '');
            $category = Str::limit(trim((string) $request->input("category.{$rowKey}", '')), 255, '');

            if ($title === '' || $category === '') {
                $skipped++;

                continue;
            }

            if ($allowedCategoryKeys !== [] && ! isset($allowedCategoryKeys[$category])) {
                $skipped++;

                continue;
            }

            $catalog     = Str::limit(trim((string) $request->input("catalog.{$rowKey}", '')), 255, '');
            $subCategory = Str::limit(trim((string) $request->input("sub_category.{$rowKey}", '')), 255, '');
            $sector      = Str::limit(trim((string) $request->input("sector.{$rowKey}", '')), 255, '');
            $description = Str::limit(trim((string) $request->input("description.{$rowKey}", '')), 10000, '');

            $image = $this->handleImageUpload(
                $request,
                "image_file.{$rowKey}",
                "image_url.{$rowKey}",
                '/images/placeholder.svg'
            );

            $productsToStore[] = [
                'catalog'      => $catalog,
                'title'        => $title,
                'category'     => $category,
                'sub_category' => $subCategory,
                'sector'       => $sector !== '' ? $sector : null,
                'description'  => $description,
                'image'        => $image,
                'price'        => 0,
                'stock'        => 0,
            ];
        }

        $savedCount = count($productsToStore);
        if ($savedCount > 0) {
            $this->products->upsertProducts($productsToStore);

            AuditLogger::log('product.bulk_create', 'Product', null, [
                'saved'   => $savedCount,
                'skipped' => $skipped,
            ]);

            $msg = "Berhasil menyimpan {$savedCount} produk secara massal!";
            if ($skipped > 0) {
                $msg .= " ({$skipped} baris dilewati karena data tidak valid.)";
            }

            return redirect()->route('admin.products')->with('success', $msg);
        }

        return redirect()->back()->with('error', 'Tidak ada data produk valid yang disimpan. Pastikan judul dan kategori terisi, dan kategori terdaftar di sistem.');
    }
}

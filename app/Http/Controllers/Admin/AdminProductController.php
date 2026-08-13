<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DataService;
use App\Traits\HandlesImageUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class AdminProductController extends Controller
{
    use HandlesImageUploads;

    protected DataService $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function productsIndex(Request $request)
    {
        $query = DB::table('products');

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
            $query->where(function ($q) use ($sector) {
                $q->where('sector', $sector)
                  ->orWhereRaw("FIND_IN_SET(?, sector)", [$sector]);
            });
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $sort = $request->input('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc')->orderBy('id', 'asc');
        } elseif ($sort === 'name_asc') {
            $query->orderBy('title', 'asc');
        } elseif ($sort === 'name_desc') {
            $query->orderBy('title', 'desc');
        } else {
            $query->orderBy('created_at', 'desc')->orderBy('id', 'desc');
        }

        $totalProducts = $query->count();
        $perPage = 15;
        $totalPages = (int)ceil($totalProducts / $perPage);
        if ($totalPages < 1) $totalPages = 1;

        $currentPage = (int)$request->input('page', 1);
        if ($currentPage < 1) $currentPage = 1;
        if ($currentPage > $totalPages) $currentPage = $totalPages;

        $offset = ($currentPage - 1) * $perPage;

        $paginatedProducts = $query->skip($offset)->take($perPage)->get()->map(fn($r) => (array) $r)->toArray();

        $sectors = $this->dataService->getSectors();

        return view('admin.products.index', [
            'products' => $paginatedProducts,
            'sectors' => $sectors,
            'search' => $search,
            'category' => $category,
            'sector' => $sector,
            'sort' => $sort,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
    }

    public function productsCreate()
    {
        $sectors = $this->dataService->getSectors();

        $product = [
            'title' => '',
            'catalog' => '',
            'category' => '',
            'sub_category' => '',
            'sector' => '',
            'image' => '',
            'gallery_images' => [],
            'description' => ''
        ];

        return view('admin.products.form', compact('sectors', 'product'));
    }

    public function productsStore(StoreProductRequest $request)
    {
        $title = $request->input('title');

        if ($this->dataService->getProductByTitle($title)) {
            return redirect()->back()->withInput()->with('error', 'Produk dengan judul tersebut sudah ada.');
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', '/images/placeholder.svg');
        $galleryImages = $this->handleMultipleImageUploads($request, 'gallery_files');

        $product = [
            'catalog' => $request->input('catalog') ?: '',
            'title' => $title,
            'description' => $request->input('description') ?: '',
            'category' => $request->input('category'),
            'sub_category' => $request->input('sub_category') ?: '',
            'sector' => $request->input('sector') ?: '',
            'image' => $image,
            'gallery_images' => $galleryImages,
            'price' => (float)$request->input('price', 0),
            'stock' => (int)$request->input('stock', 0),
        ];

        $this->dataService->addProduct($product);

        return redirect()->route('admin.products')->with('success', 'Produk baru berhasil ditambahkan!');
    }

    public function productsEdit(int $id)
    {
        $product = $this->dataService->getProductById($id);
        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Produk tidak ditemukan.');
        }
        $sectors = $this->dataService->getSectors();
        return view('admin.products.form', compact('product', 'sectors'));
    }

    public function productsUpdate(UpdateProductRequest $request, int $id)
    {
        $product = $this->dataService->getProductById($id);
        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Produk tidak ditemukan.');
        }

        $newTitle = $request->input('title');
        $existing = $this->dataService->getProductByTitle($newTitle);

        if ($existing && (int)($existing['id'] ?? 0) !== $id) {
            return redirect()->back()->withInput()->with('error', 'Produk dengan judul baru tersebut sudah ada.');
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', $product['image']);

        // Keep existing gallery images except those the admin explicitly marked for removal
        $existingGallery = $product['gallery_images'] ?? [];
        $toRemove = (array) $request->input('remove_gallery', []);
        if (!empty($toRemove)) {
            $existingGallery = array_values(array_diff($existingGallery, $toRemove));
        }
        $newGalleryImages = $this->handleMultipleImageUploads($request, 'gallery_files');
        $galleryImages = array_values(array_slice(array_merge($existingGallery, $newGalleryImages), 0, 10));

        $updatedProduct = [
            'catalog' => $request->input('catalog') ?: '',
            'title' => $newTitle,
            'description' => $request->input('description') ?: '',
            'category' => $request->input('category'),
            'sub_category' => $request->input('sub_category') ?: '',
            'sector' => $request->input('sector') ?: '',
            'image' => $image,
            'gallery_images' => $galleryImages,
            'price' => (float)$request->input('price', 0),
            'stock' => (int)$request->input('stock', 0),
        ];

        $this->dataService->updateProductById($id, $updatedProduct);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diperbarui!');
    }

    public function productsDestroy(int $id)
    {
        $this->dataService->deleteProductById($id);
        return redirect()->route('admin.products')->with('success', 'Produk berhasil dihapus!');
    }

    public function productsCreateBulk()
    {
        $sectors = $this->dataService->getSectors();
        return view('admin.products.bulk-form', compact('sectors'));
    }

    public function productsStoreBulk(Request $request)
    {
        $titles = $request->input('title', []);
        $productsToStore = [];

        foreach ($titles as $id => $title) {
            $title = trim($title);
            $category = trim($request->input("category.{$id}", ''));

            if (empty($title) || empty($category)) {
                continue;
            }

            $catalog     = trim($request->input("catalog.{$id}", ''));
            $subCategory = trim($request->input("sub_category.{$id}", ''));
            $sector      = trim($request->input("sector.{$id}", ''));
            $description = trim($request->input("description.{$id}", ''));

            // Use HandlesImageUploads trait — same security, WebP conversion, size limits as single upload
            $image = $this->handleImageUpload(
                $request,
                "image_file.{$id}",
                "image_url.{$id}",
                '/images/placeholder.svg'
            );

            $productsToStore[] = [
                'catalog'      => $catalog,
                'title'        => $title,
                'category'     => $category,
                'sub_category' => $subCategory,
                'sector'       => $sector ?: null,
                'description'  => $description,
                'image'        => $image,
                'price'        => 0,
                'stock'        => 0,
            ];
        }

        $savedCount = count($productsToStore);
        if ($savedCount > 0) {
            $this->dataService->upsertProducts($productsToStore);
            return redirect()->route('admin.products')->with('success', "Berhasil menyimpan $savedCount produk secara massal!");
        }

        return redirect()->back()->with('error', 'Tidak ada data produk valid yang disimpan. Harap isi minimal judul dan kategori produk.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DataService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class AdminProductController extends Controller
{
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
                  ->orWhere('sector', 'like', "%{$sector}%")
                  ->orWhere('sector', 'like', "%,{$sector},%")
                  ->orWhere('sector', 'like', "%,{$sector}")
                  ->orWhere('sector', 'like', "{$sector},%");
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

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80');

        $product = [
            'catalog' => $request->input('catalog') ?: '',
            'title' => $title,
            'description' => $request->input('description') ?: '',
            'category' => $request->input('category'),
            'sub_category' => $request->input('sub_category') ?: '',
            'sector' => $request->input('sector') ?: '',
            'image' => $image,
            'price' => (float)$request->input('price', 0),
            'stock' => (int)$request->input('stock', 0),
        ];

        $this->dataService->addProduct($product);

        return redirect()->route('admin.products')->with('success', 'Produk baru berhasil ditambahkan!');
    }

    public function productsEdit(string $title)
    {
        $product = $this->dataService->getProductByTitle($title);
        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Produk tidak ditemukan.');
        }
        $sectors = $this->dataService->getSectors();
        return view('admin.products.form', compact('product', 'sectors'));
    }

    public function productsUpdate(UpdateProductRequest $request, string $title)
    {
        $product = $this->dataService->getProductByTitle($title);
        if (!$product) {
            return redirect()->route('admin.products')->with('error', 'Produk tidak ditemukan.');
        }

        $newTitle = $request->input('title');

        if ($newTitle !== $title && $this->dataService->getProductByTitle($newTitle)) {
            return redirect()->back()->withInput()->with('error', 'Produk dengan judul baru tersebut sudah ada.');
        }

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', $product['image']);

        $updatedProduct = [
            'catalog' => $request->input('catalog') ?: '',
            'title' => $newTitle,
            'description' => $request->input('description') ?: '',
            'category' => $request->input('category'),
            'sub_category' => $request->input('sub_category') ?: '',
            'sector' => $request->input('sector') ?: '',
            'image' => $image,
            'price' => (float)$request->input('price', 0),
            'stock' => (int)$request->input('stock', 0),
        ];

        $this->dataService->updateProduct($title, $updatedProduct);

        return redirect()->route('admin.products')->with('success', 'Produk berhasil diperbarui!');
    }

    public function productsDestroy(string $title)
    {
        $this->dataService->deleteProduct($title);
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

            $catalog = trim($request->input("catalog.{$id}", ''));
            $subCategory = trim($request->input("sub_category.{$id}", ''));
            $sector = trim($request->input("sector.{$id}", ''));
            $description = trim($request->input("description.{$id}", ''));
            $imageUrl = trim($request->input("image_url.{$id}", ''));

            $image = null;
            if ($request->hasFile("image_file.{$id}") && $request->file("image_file.{$id}")->isValid()) {
                $file = $request->file("image_file.{$id}");

                $ext = strtolower($file->getClientOriginalExtension());
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (!in_array($ext, $allowedExtensions, true)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        "image_file.{$id}" => ['Format file tidak didukung. Harap unggah gambar dengan format: jpg, jpeg, png, gif, webp.']
                    ]);
                }

                $mime = $file->getMimeType();
                $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($mime, $allowedMimes, true)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        "image_file.{$id}" => ['Tipe file tidak valid. Harap unggah file gambar yang valid.']
                    ]);
                }

                $fileName = time() . '_' . Str::random(8) . '.' . $ext;
                $uploadPath = public_path('uploads');
                if (!File::exists($uploadPath)) {
                    File::makeDirectory($uploadPath, 0755, true);
                }
                $file->move($uploadPath, $fileName);
                $image = asset('uploads/' . $fileName);
            } else {
                $image = $imageUrl;
            }

            if (empty($image)) {
                $image = 'https://images.unsplash.com/photo-1532187863486-abf9dbad1b69?auto=format&fit=crop&w=400&q=80';
            }

            $productsToStore[] = [
                'catalog' => $catalog,
                'title' => $title,
                'category' => $category,
                'sub_category' => $subCategory,
                'sector' => $sector ?: null,
                'description' => $description,
                'image' => $image
            ];
        }

        $savedCount = count($productsToStore);
        if ($savedCount > 0) {
            $this->dataService->upsertProducts($productsToStore);
            return redirect()->route('admin.products')->with('success', "Berhasil menyimpan $savedCount produk secara massal!");
        }

        return redirect()->back()->with('error', 'Tidak ada data produk valid yang disimpan. Harap isi minimal judul dan kategori produk.');
    }

    protected function handleImageUpload(Request $request, string $fileKey, string $urlKey, ?string $fallback = null): ?string
    {
        if ($request->hasFile($fileKey) && $request->file($fileKey)->isValid()) {
            $file = $request->file($fileKey);

            $ext = strtolower($file->getClientOriginalExtension());
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            if (!in_array($ext, $allowedExtensions, true)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    $fileKey => ['Format file tidak didukung. Harap unggah gambar dengan format: jpg, jpeg, png, gif, webp.']
                ]);
            }

            $mime = $file->getMimeType();
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($mime, $allowedMimes, true)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    $fileKey => ['Tipe file tidak valid. Harap unggah file gambar yang valid.']
                ]);
            }

            $fileName = time() . '_' . Str::random(8) . '.' . $ext;

            $uploadPath = public_path('uploads');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file->move($uploadPath, $fileName);
            return asset('uploads/' . $fileName);
        }

        return $request->input($urlKey, $fallback);
    }
}

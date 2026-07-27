<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DataService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

use App\Http\Requests\StoreSectorRequest;
use App\Http\Requests\UpdateSectorRequest;

class AdminSectorController extends Controller
{
    protected DataService $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function sectorsIndex()
    {
        $sectors = $this->dataService->getSectors();
        return view('admin.sectors.index', compact('sectors'));
    }

    public function sectorsCreate()
    {
        return view('admin.sectors.form');
    }

    public function sectorsStore(StoreSectorRequest $request)
    {
        $id = strtolower($request->input('id'));

        if ($this->dataService->getSectorById($id)) {
            return redirect()->back()->withInput()->with('error', 'Sektor dengan ID tersebut sudah ada.');
        }

        $descRaw = $request->input('description', '');
        $description = array_filter(array_map('trim', explode("\n", $descRaw)));

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', '');

        $sector = [
            'id' => $id,
            'name' => $request->input('name'),
            'description' => $description,
            'image' => $image
        ];

        $this->dataService->addSector($sector);

        return redirect()->route('admin.sectors')->with('success', 'Sektor industri baru berhasil ditambahkan!');
    }

    public function sectorsEdit(string $id)
    {
        $sector = $this->dataService->getSectorById($id);
        if (!$sector) {
            return redirect()->route('admin.sectors')->with('error', 'Sektor tidak ditemukan.');
        }
        return view('admin.sectors.form', compact('sector'));
    }

    public function sectorsUpdate(UpdateSectorRequest $request, string $id)
    {
        $sector = $this->dataService->getSectorById($id);
        if (!$sector) {
            return redirect()->route('admin.sectors')->with('error', 'Sektor tidak ditemukan.');
        }

        $descRaw = $request->input('description', '');
        $description = array_filter(array_map('trim', explode("\n", $descRaw)));

        $existingImg = $sector['image'] ?? '';
        $image = $this->handleImageUpload($request, 'image_file', 'image_url', $existingImg);

        $updatedSector = [
            'id' => $id,
            'name' => $request->input('name'),
            'description' => $description,
            'image' => $image
        ];

        $this->dataService->updateSector($id, $updatedSector);

        return redirect()->route('admin.sectors')->with('success', 'Sektor berhasil diperbarui!');
    }

    public function sectorsDestroy(string $id)
    {
        $this->dataService->deleteSector($id);
        return redirect()->route('admin.sectors')->with('success', 'Sektor berhasil dihapus!');
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

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSectorRequest;
use App\Http\Requests\UpdateSectorRequest;
use App\Services\AuditLogger;
use App\Services\SectorService;
use App\Traits\HandlesImageUploads;

class AdminSectorController extends Controller
{
    use HandlesImageUploads;

    protected SectorService $sectors;

    public function __construct(SectorService $sectors)
    {
        $this->sectors = $sectors;
    }

    public function sectorsIndex()
    {
        $sectors = $this->sectors->getSectors();

        return view('admin.sectors.index', compact('sectors'));
    }

    public function sectorsCreate()
    {
        return view('admin.sectors.form');
    }

    public function sectorsStore(StoreSectorRequest $request)
    {
        $id = strtolower($request->input('id'));

        if ($this->sectors->getSectorById($id)) {
            return redirect()->back()->withInput()->with('error', 'Sektor dengan ID tersebut sudah ada.');
        }

        $descRaw = $request->input('description', '');
        $description = array_filter(array_map('trim', explode("\n", $descRaw)));

        $image = $this->handleImageUpload($request, 'image_file', 'image_url', '');

        $sector = [
            'id' => $id,
            'name' => $request->input('name'),
            'description' => $description,
            'image' => $image,
        ];

        $this->sectors->addSector($sector);

        AuditLogger::log('sector.create', 'Sector', $id, [
            'name' => $sector['name'],
            'id' => $id,
        ]);

        return redirect()->route('admin.sectors')->with('success', 'Sektor industri baru berhasil ditambahkan!');
    }

    public function sectorsEdit(string $id)
    {
        $sector = $this->sectors->getSectorById($id);
        if (! $sector) {
            return redirect()->route('admin.sectors')->with('error', 'Sektor tidak ditemukan.');
        }

        return view('admin.sectors.form', compact('sector'));
    }

    public function sectorsUpdate(UpdateSectorRequest $request, string $id)
    {
        $sector = $this->sectors->getSectorById($id);
        if (! $sector) {
            return redirect()->route('admin.sectors')->with('error', 'Sektor tidak ditemukan.');
        }

        $oldName = $sector['name'] ?? null;
        $descRaw = $request->input('description', '');
        $description = array_filter(array_map('trim', explode("\n", $descRaw)));

        $existingImg = $sector['image'] ?? '';
        $image = $this->handleImageUpload($request, 'image_file', 'image_url', $existingImg);

        $updatedSector = [
            'id' => $id,
            'name' => $request->input('name'),
            'description' => $description,
            'image' => $image,
        ];

        $this->sectors->updateSector($id, $updatedSector);

        AuditLogger::log('sector.update', 'Sector', $id, [
            'old_name' => $oldName,
            'new_name' => $updatedSector['name'],
            'id' => $id,
        ]);

        return redirect()->route('admin.sectors')->with('success', 'Sektor berhasil diperbarui!');
    }

    public function sectorsDestroy(string $id)
    {
        $sector = $this->sectors->getSectorById($id);
        $name = $sector['name'] ?? null;

        $this->sectors->deleteSector($id);

        AuditLogger::log('sector.delete', 'Sector', $id, [
            'name' => $name,
            'id' => $id,
        ]);

        return redirect()->route('admin.sectors')->with('success', 'Sektor berhasil dihapus!');
    }
}

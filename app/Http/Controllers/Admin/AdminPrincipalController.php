<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrincipalRequest;
use App\Http\Requests\UpdatePrincipalRequest;
use App\Models\Principal;
use App\Models\Product;
use App\Services\AuditLogger;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminPrincipalController extends Controller
{
    use HandlesImageUploads;

    /** Cache key must match resources/views/partials/home-principals.blade.php */
    private const ACTIVE_PRINCIPALS_CACHE = 'active_principals_v5';

    public function index(Request $request)
    {
        $search = $request->input('s');

        $principals = Principal::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->get();

        return view('admin.principals.index', compact('principals', 'search'));
    }

    public function create()
    {
        return view('admin.principals.form');
    }

    public function store(StorePrincipalRequest $request)
    {
        $logo = $this->handleImageUpload($request, 'logo_file', 'logo_url', null);

        $principal = Principal::create([
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'logo' => $logo,
            'status' => $request->input('status', 'online'),
        ]);

        Cache::forget(self::ACTIVE_PRINCIPALS_CACHE);

        AuditLogger::log('principal.create', 'Principal', $principal->id, [
            'name' => $principal->name,
            'status' => $principal->status,
        ]);

        return redirect()->route('admin.principals')->with('success', 'Prinsipal / Mitra baru berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        $principal = Principal::find($id);
        if (! $principal) {
            return redirect()->route('admin.principals')->with('error', 'Prinsipal tidak ditemukan.');
        }

        return view('admin.principals.form', compact('principal'));
    }

    public function update(UpdatePrincipalRequest $request, int $id)
    {
        $principal = Principal::find($id);
        if (! $principal) {
            return redirect()->route('admin.principals')->with('error', 'Prinsipal tidak ditemukan.');
        }

        $logo = $this->handleImageUpload($request, 'logo_file', 'logo_url', $principal->logo);

        $oldName = $principal->name;

        $principal->update([
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'logo' => $logo,
            'status' => $request->input('status', 'online'),
        ]);

        Cache::forget(self::ACTIVE_PRINCIPALS_CACHE);

        AuditLogger::log('principal.update', 'Principal', $principal->id, [
            'old_name' => $oldName,
            'new_name' => $principal->name,
            'status' => $principal->status,
        ]);

        return redirect()->route('admin.principals')->with('success', 'Prinsipal / Mitra berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        $principal = Principal::find($id);
        if (! $principal) {
            return redirect()->route('admin.principals')->with('error', 'Prinsipal tidak ditemukan.');
        }

        $usedCount = Product::where('principal_id', $id)->count();
        if ($usedCount > 0) {
            return redirect()->route('admin.principals')->with('error', "Prinsipal \"{$principal->name}\" masih terhubung dengan {$usedCount} produk katalog. Silakan pindahkan atau ubah prinsipal pada produk terkait terlebih dahulu.");
        }

        $name = $principal->name;
        $principal->delete();

        Cache::forget(self::ACTIVE_PRINCIPALS_CACHE);

        AuditLogger::log('principal.delete', 'Principal', $id, [
            'name' => $name,
        ]);

        return redirect()->route('admin.principals')->with('success', 'Prinsipal berhasil dihapus!');
    }
}

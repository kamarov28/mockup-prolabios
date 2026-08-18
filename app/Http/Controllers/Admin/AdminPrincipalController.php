<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuditLogger;
use App\Traits\HandlesImageUploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AdminPrincipalController extends Controller
{
    use HandlesImageUploads;

    public function index(Request $request)
    {
        $search = $request->input('s');
        $query = DB::table('principals');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%");
        }

        $principals = $query->orderBy('name', 'asc')->get();

        return view('admin.principals.index', compact('principals', 'search'));
    }

    public function create()
    {
        return view('admin.principals.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:online,draft',
        ]);

        $logo = $this->handleImageUpload($request, 'logo_file', 'logo_url', null);

        $id = DB::table('principals')->insertGetId([
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'logo' => $logo,
            'status' => $request->input('status', 'online'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        AuditLogger::log('principal.create', 'Principal', $id, [
            'name' => $request->input('name'),
            'status' => $request->input('status', 'online'),
        ]);

        return redirect()->route('admin.principals')->with('success', 'Prinsipal / Mitra baru berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        $principal = DB::table('principals')->where('id', $id)->first();
        if (! $principal) {
            return redirect()->route('admin.principals')->with('error', 'Prinsipal tidak ditemukan.');
        }

        return view('admin.principals.form', compact('principal'));
    }

    public function update(Request $request, int $id)
    {
        $principal = DB::table('principals')->where('id', $id)->first();
        if (! $principal) {
            return redirect()->route('admin.principals')->with('error', 'Prinsipal tidak ditemukan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:online,draft',
        ]);

        $logo = $this->handleImageUpload($request, 'logo_file', 'logo_url', $principal->logo ?? null);

        DB::table('principals')->where('id', $id)->update([
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'logo' => $logo,
            'status' => $request->input('status', 'online'),
            'updated_at' => now(),
        ]);

        Cache::forget('active_principals_v4');

        AuditLogger::log('principal.update', 'Principal', $id, [
            'old_name' => $principal->name,
            'new_name' => $request->input('name'),
            'status' => $request->input('status', 'online'),
        ]);

        return redirect()->route('admin.principals')->with('success', 'Prinsipal / Mitra berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        $principal = DB::table('principals')->where('id', $id)->first();
        $name = $principal?->name;

        DB::table('principals')->where('id', $id)->delete();
        Cache::forget('active_principals_v4');

        AuditLogger::log('principal.delete', 'Principal', $id, [
            'name' => $name,
        ]);

        return redirect()->route('admin.principals')->with('success', 'Prinsipal berhasil dihapus!');
    }
}

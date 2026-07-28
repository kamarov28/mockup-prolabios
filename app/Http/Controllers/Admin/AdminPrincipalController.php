<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminPrincipalController extends Controller
{
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

        $logo = $this->handleLogoUpload($request);

        DB::table('principals')->insert([
            'name'       => $request->input('name'),
            'address'    => $request->input('address'),
            'logo'       => $logo,
            'status'     => $request->input('status', 'online'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.principals')->with('success', 'Prinsipal / Mitra baru berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        $principal = DB::table('principals')->where('id', $id)->first();
        if (!$principal) {
            return redirect()->route('admin.principals')->with('error', 'Prinsipal tidak ditemukan.');
        }

        return view('admin.principals.form', compact('principal'));
    }

    public function update(Request $request, int $id)
    {
        $principal = DB::table('principals')->where('id', $id)->first();
        if (!$principal) {
            return redirect()->route('admin.principals')->with('error', 'Prinsipal tidak ditemukan.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:online,draft',
        ]);

        $logo = $this->handleLogoUpload($request, $principal->logo);

        DB::table('principals')->where('id', $id)->update([
            'name'       => $request->input('name'),
            'address'    => $request->input('address'),
            'logo'       => $logo,
            'status'     => $request->input('status', 'online'),
            'updated_at' => now(),
        ]);

        \Illuminate\Support\Facades\Cache::forget('active_principals_v4');

        return redirect()->route('admin.principals')->with('success', 'Prinsipal / Mitra berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        DB::table('principals')->where('id', $id)->delete();
        \Illuminate\Support\Facades\Cache::forget('active_principals_v4');
        return redirect()->route('admin.principals')->with('success', 'Prinsipal berhasil dihapus!');
    }

    protected function handleLogoUpload(Request $request, ?string $fallback = null): ?string
    {
        if ($request->hasFile('logo_file') && $request->file('logo_file')->isValid()) {
            $file = $request->file('logo_file');
            $ext = strtolower($file->getClientOriginalExtension());
            $fileName = 'principal_' . time() . '_' . Str::random(6) . '.' . $ext;

            $uploadPath = public_path('uploads');
            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $file->move($uploadPath, $fileName);
            return asset('uploads/' . $fileName);
        }

        return $request->input('logo_url', $fallback);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class AdminProductCategoryController extends Controller
{
    public function index()
    {
        $parents = ProductCategory::whereNull('parent_id')
            ->withCount('children')
            ->with(['children' => fn ($q) => $q->orderBy('sort_order')->orderBy('name')])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.categories.index', compact('parents'));
    }

    public function create(Request $request)
    {
        $parents = ProductCategory::whereNull('parent_id')->orderBy('sort_order')->orderBy('name')->get();
        $selectedParentId = $request->query('parent_id');

        return view('admin.categories.form', [
            'category'        => null,
            'parents'         => $parents,
            'selectedParentId' => $selectedParentId,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:150',
            'key'        => 'nullable|string|max:100|regex:/^[a-z0-9\-]+$/|unique:product_categories,key',
            'parent_id'  => [
                'nullable',
                'exists:product_categories,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $parent = ProductCategory::find($value);
                        if ($parent && !is_null($parent->parent_id)) {
                            $fail('Subkategori tidak bisa dijadikan induk kategori (maksimal 2 tingkat hirarki).');
                        }
                    }
                },
            ],
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'key.regex'  => 'Key hanya boleh berisi huruf kecil, angka, dan tanda hubung (-)',
            'key.unique' => 'Key ini sudah dipakai kategori lain.',
        ]);

        $key = $request->input('key')
            ? Str::slug($request->input('key'))
            : Str::slug($request->input('name'));

        // Ensure key uniqueness if auto-generated
        if (ProductCategory::where('key', $key)->exists()) {
            $key = $key . '-' . Str::random(4);
        }

        $cat = ProductCategory::create([
            'key'        => $key,
            'name'       => trim($request->input('name')),
            'parent_id'  => $request->input('parent_id') ?: null,
            'sort_order' => (int) $request->input('sort_order', 0),
        ]);

        AuditLogger::log('product_category.create', 'ProductCategory', $cat->id, [
            'key'       => $cat->key,
            'name'      => $cat->name,
            'parent_id' => $cat->parent_id,
        ]);

        Cache::forget('categories_structure');

        $label = $cat->parent_id ? 'Sub-kategori' : 'Kategori';

        return redirect()->route('admin.categories.index')
            ->with('success', "{$label} \"{$cat->name}\" berhasil ditambahkan!");
    }

    public function edit(int $id)
    {
        $category = ProductCategory::findOrFail($id);
        $parents  = ProductCategory::whereNull('parent_id')
            ->where('id', '!=', $id)  // Kategori tidak bisa jadi anak dirinya sendiri
            ->orderBy('sort_order')->orderBy('name')
            ->get();

        return view('admin.categories.form', [
            'category'         => $category,
            'parents'          => $parents,
            'selectedParentId' => $category->parent_id,
        ]);
    }

    public function update(Request $request, int $id)
    {
        $category = ProductCategory::findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:150',
            'key'        => "nullable|string|max:100|regex:/^[a-z0-9\-]+$/|unique:product_categories,key,{$id}",
            'parent_id'  => [
                'nullable',
                'exists:product_categories,id',
                function ($attribute, $value, $fail) use ($id) {
                    if ($value) {
                        if ((int) $value === $id) {
                            $fail('Kategori tidak bisa menjadi induk dari dirinya sendiri.');
                            return;
                        }
                        $parent = ProductCategory::find($value);
                        if ($parent && !is_null($parent->parent_id)) {
                            $fail('Subkategori tidak bisa dijadikan induk kategori (maksimal 2 tingkat hirarki).');
                        }
                    }
                },
            ],
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'key.regex'  => 'Key hanya boleh berisi huruf kecil, angka, dan tanda hubung (-)',
            'key.unique' => 'Key ini sudah dipakai kategori lain.',
        ]);

        $newParentId = $request->input('parent_id') ?: null;

        // Cegah kategori yang memiliki anak diubah menjadi subkategori
        if ($newParentId && $category->children()->exists()) {
            return back()->withInput()->withErrors(['parent_id' => 'Kategori yang memiliki subkategori tidak dapat dijadikan subkategori. Pindahkan subkategorinya terlebih dahulu.']);
        }

        $key = $request->input('key')
            ? Str::slug($request->input('key'))
            : $category->key;

        $old = $category->toArray();
        $oldKey = $old['key'];

        \Illuminate\Support\Facades\DB::transaction(function () use ($category, $key, $oldKey, $request, $newParentId) {
            $category->update([
                'key'        => $key,
                'name'       => trim($request->input('name')),
                'parent_id'  => $newParentId,
                'sort_order' => (int) $request->input('sort_order', 0),
            ]);

            // Cascade key updates to products referencing the old category/sub_category key
            if ($oldKey !== $key) {
                Product::where('category', $oldKey)
                    ->update(['category' => $key]);

                Product::where('sub_category', $oldKey)
                    ->update(['sub_category' => $key]);
            }
        });

        AuditLogger::log('product_category.update', 'ProductCategory', $id, [
            'old_name' => $old['name'],
            'new_name' => $category->name,
            'old_key'  => $old['key'],
            'new_key'  => $category->key,
        ]);

        Cache::forget('categories_structure');

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$category->name}\" berhasil diperbarui!");
    }

    public function destroy(int $id)
    {
        $category = ProductCategory::with('children')->withCount('children')->findOrFail($id);

        // Kumpulkan semua keys (induk + anak-anaknya) untuk verifikasi relasi produk
        $allKeys = collect([$category->key])
            ->merge($category->children->pluck('key'))
            ->unique()
            ->all();

        // Cek apakah masih ada produk yang menggunakan key kategori atau subkategori terkait
        $usedByProducts = \Illuminate\Support\Facades\DB::table('products')
            ->whereIn('category', $allKeys)
            ->orWhereIn('sub_category', $allKeys)
            ->exists();

        if ($usedByProducts) {
            return back()->with('error', "Kategori \"{$category->name}\" atau subkategorinya tidak bisa dihapus karena masih digunakan oleh produk. Pindahkan produk terkait terlebih dahulu.");
        }

        $name = $category->name;

        // Cascade delete children is handled by DB foreign key (onDelete cascade)
        $category->delete();

        AuditLogger::log('product_category.delete', 'ProductCategory', $id, ['name' => $name]);
        Cache::forget('categories_structure');

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori \"{$name}\" berhasil dihapus.");
    }

    /**
     * API endpoint: return subcategories as JSON for the product form dropdown.
     * GET /admin/api/subcategories?parent_id=X
     */
    public function apiSubcategories(Request $request)
    {
        $parentId = $request->query('parent_id');

        if (! $parentId) {
            return response()->json([]);
        }

        $subs = ProductCategory::where('parent_id', $parentId)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'key', 'name']);

        return response()->json($subs);
    }
}

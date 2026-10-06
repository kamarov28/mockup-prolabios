<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query()->where('is_admin', true);

        $search = trim((string) $request->input('s', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $roleFilter = $request->input('role');
        if ($roleFilter && in_array($roleFilter, User::ALL_ROLES, true)) {
            $query->where('role', $roleFilter);
        }

        $users = $query->orderBy('name')->get();

        $roleCounts = [
            User::ROLE_SUPER_ADMIN => User::where('is_admin', true)->where('role', User::ROLE_SUPER_ADMIN)->count(),
            User::ROLE_SALES => User::where('is_admin', true)->where('role', User::ROLE_SALES)->count(),
            User::ROLE_CATALOG => User::where('is_admin', true)->where('role', User::ROLE_CATALOG)->count(),
            User::ROLE_CONTENT => User::where('is_admin', true)->where('role', User::ROLE_CONTENT)->count(),
        ];

        return view('admin.users.index', [
            'users' => $users,
            'roleCounts' => $roleCounts,
            'search' => $search,
            'roleFilter' => $roleFilter,
            'allRoles' => User::ALL_ROLES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'role' => ['required', 'string', Rule::in(User::ALL_ROLES)],
            'password' => ['required', 'string', Password::min(8)->letters()->numbers()],
        ], [
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'password.min' => 'Kata sandi minimal 8 karakter dan mengandung kombinasi huruf serta angka.',
            'role.in' => 'Peran admin tidak valid.',
        ]);

        $user = new User([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        $user->is_admin = true;
        $user->role = $validated['role'];
        $user->save();

        AuditLogger::log('create_admin_user', 'User', $user->id, [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ]);

        return redirect()->route('admin.users.index')->with('success', "Akun admin {$user->name} berhasil dibuat.");
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', 'string', Rule::in(User::ALL_ROLES)],
            'password' => ['nullable', 'string', Password::min(8)->letters()->numbers()],
        ], [
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'password.min' => 'Kata sandi baru minimal 8 karakter dan mengandung kombinasi huruf serta angka.',
            'role.in' => 'Peran admin tidak valid.',
        ]);

        // Security check: cannot demote own active Super Admin account
        if ($user->id === auth()->id() && $validated['role'] !== User::ROLE_SUPER_ADMIN) {
            return back()->with('error', 'Anda tidak dapat menurunkan peran Super Admin pada akun Anda sendiri.');
        }

        // Security check: prevent leaving zero Super Admins
        if ($user->role === User::ROLE_SUPER_ADMIN && $validated['role'] !== User::ROLE_SUPER_ADMIN) {
            $superAdminCount = User::where('role', User::ROLE_SUPER_ADMIN)->count();
            if ($superAdminCount <= 1) {
                return back()->with('error', 'Tidak dapat mengubah peran satu-satunya Super Admin yang tersisa.');
            }
        }

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);
        $user->role = $validated['role'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        AuditLogger::log('update_admin_user', 'User', $user->id, [
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'password_changed' => ! empty($validated['password']),
        ]);

        return redirect()->route('admin.users.index')->with('success', "Data akun {$user->name} berhasil diperbarui.");
    }

    public function destroy(int $id): RedirectResponse
    {
        $user = User::findOrFail($id);

        // Security check: cannot delete own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif.');
        }

        // Security check: cannot delete the last Super Admin
        if ($user->isSuperAdmin()) {
            $superAdminCount = User::where('role', User::ROLE_SUPER_ADMIN)->count();
            if ($superAdminCount <= 1) {
                return back()->with('error', 'Tidak dapat menghapus satu-satunya Super Admin yang tersisa di sistem.');
            }
        }

        $userName = $user->name;
        $userRole = $user->role;
        $userId = $user->id;

        $user->delete();

        AuditLogger::log('delete_admin_user', 'User', $userId, [
            'name' => $userName,
            'role' => $userRole,
        ]);

        return redirect()->route('admin.users.index')->with('success', "Akun admin {$userName} berhasil dihapus.");
    }
}

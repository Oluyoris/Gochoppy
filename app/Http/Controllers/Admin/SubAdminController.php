<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SubAdminController extends Controller
{
    public function index()
    {
        $subAdmins = AdminUser::where('is_super_admin', false)
                              ->orderBy('created_at', 'desc')
                              ->get();

        return view('admin.sub-admins.index', compact('subAdmins'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'super-admin')->get(); // exclude super-admin role

        return view('admin.sub-admins.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:admin_users,email',
            'password' => 'required|string|min:8|confirmed',
            'roles'    => 'array',
            'roles.*'  => 'exists:roles,name',
        ]);

        $admin = AdminUser::create([
            'name'           => $validated['name'],
            'email'          => $validated['email'],
            'password'       => Hash::make($validated['password']),
            'is_super_admin' => false,
        ]);

        // Assign selected roles
        if (!empty($validated['roles'])) {
            $admin->assignRole($validated['roles']);
        }

        return redirect()->route('admin.sub-admins.index')
                         ->with('success', 'Sub-admin created successfully!');
    }

    public function edit(AdminUser $subAdmin)
    {
        if ($subAdmin->is_super_admin) {
            abort(403, 'Super admin cannot be edited here.');
        }

        $roles = Role::where('name', '!=', 'super-admin')->get();
        $currentRoles = $subAdmin->getRoleNames()->toArray();

        return view('admin.sub-admins.edit', compact('subAdmin', 'roles', 'currentRoles'));
    }

    public function update(Request $request, AdminUser $subAdmin)
    {
        if ($subAdmin->is_super_admin) {
            abort(403, 'Super admin cannot be edited here.');
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:admin_users,email,' . $subAdmin->id,
            'password' => 'nullable|string|min:8|confirmed',
            'roles'    => 'array',
            'roles.*'  => 'exists:roles,name',
        ]);

        $updateData = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $subAdmin->update($updateData);

        // Sync roles (remove old, add new)
        $subAdmin->syncRoles($validated['roles'] ?? []);

        return redirect()->route('admin.sub-admins.index')
                         ->with('success', 'Sub-admin updated successfully!');
    }

    public function destroy(AdminUser $subAdmin)
    {
        if ($subAdmin->is_super_admin) {
            abort(403, 'Cannot delete super admin.');
        }

        $subAdmin->delete();

        return redirect()->route('admin.sub-admins.index')
                         ->with('success', 'Sub-admin deleted successfully!');
    }
}
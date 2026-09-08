<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\WebsiteBuilder\WbStaff;

class WbRoleController extends Controller
{
    public function index()
    {
        $this->ensureRolesTableExists();
        $roles = Role::orderBy('id', 'desc')->get();
        return view('website_builder.admin.staff.roles', compact('roles'));
    }

    private function ensureRolesTableExists(): void
    {
        try {
            if (!\Illuminate\Support\Facades\Schema::hasTable('roles')) {
                \Illuminate\Support\Facades\DB::statement("
                    CREATE TABLE IF NOT EXISTS `roles` (
                      `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                      `name` varchar(255) DEFAULT NULL,
                      `permissions` text DEFAULT NULL,
                      `created_at` timestamp NULL DEFAULT NULL,
                      `updated_at` timestamp NULL DEFAULT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
                ");
            }
        } catch (\Throwable $e) {}
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $role = new Role();
        $role->name = $request->name;
        $role->permissions = json_encode($request->permissions ?? []);
        $role->save();

        return redirect()->back()->with('success', __('Role created successfully.'));
    }

    public function updatePermissions(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $permissions = json_encode($request->permissions ?? []);
        $role->permissions = $permissions;
        $role->save();

        return redirect()->back()->with('success', __('Permissions updated successfully for role: ') . $role->name);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        
        // Check if admins or staff are using this role
        if ($role->admins()->count() > 0 || WbStaff::where('role', $role->name)->count() > 0) {
            return redirect()->back()->with('alert', __('Cannot delete role assigned to active admin/staff members.'));
        }

        $role->delete();
        return redirect()->back()->with('success', __('Role deleted successfully.'));
    }
}

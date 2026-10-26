<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBuilder\WbStaff;
use App\Models\Role;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class StaffController extends Controller
{
    public function index()
    {
        $staffMembers = WbStaff::orderBy('id', 'desc')->paginate(15);
        $roles = Role::orderBy('name', 'asc')->get();
        return view('website_builder.admin.staff.index', compact('staffMembers', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:wb_staff,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|string',
        ]);

        $role = null;
        if ($request->filled('role_id')) {
            $role = Role::find($request->role_id);
        }
        if (!$role && $request->filled('role')) {
            $role = Role::where('name', $request->role)->first();
        }

        $roleName = $role ? $role->name : $request->role;
        $roleId = $role ? $role->id : null;
        $rolePerms = $role ? (is_string($role->permissions) ? json_decode($role->permissions, true) : $role->permissions) : ['Landing & Colors', 'Registered Clients'];
        if (!is_array($rolePerms)) $rolePerms = [];

        // 1. Create in wb_staff
        WbStaff::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $roleName,
            'permissions' => $rolePerms,
            'is_active'   => true,
        ]);

        // 2. Sync to admins table so the staff member can authenticate via Auth::guard('admin')
        if (Schema::hasTable('admins')) {
            $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', explode('@', $request->email)[0]));
            if (empty($baseUsername)) $baseUsername = 'staff';
            $username = $baseUsername;
            $counter = 1;
            while (Admin::where('username', $username)->exists()) {
                $username = $baseUsername . $counter;
                $counter++;
            }

            Admin::updateOrCreate(
                ['email' => $request->email],
                [
                    'username'   => $username,
                    'first_name' => $request->name,
                    'last_name'  => '',
                    'password'   => Hash::make($request->password),
                    'role_id'    => $roleId,
                    'status'     => 1,
                ]
            );
        }

        return redirect()->back()->with('success', 'Staff member created successfully with role ' . $roleName);
    }

    public function destroy($id)
    {
        $staff = WbStaff::findOrFail($id);
        $email = $staff->email;
        $staff->delete();

        if (Schema::hasTable('admins')) {
            $adminUser = Admin::where('email', $email)->first();
            // Safe guard: only delete if they have an assigned role (not the primary superadmin)
            if ($adminUser && !empty($adminUser->role_id)) {
                $adminUser->delete();
            }
        }

        return redirect()->back()->with('success', 'Staff member deleted.');
    }
}

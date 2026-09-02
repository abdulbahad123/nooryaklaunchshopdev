<?php

namespace App\Http\Controllers\WebsiteBuilder\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteBuilder\WbStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    public function index()
    {
        $staffMembers = WbStaff::orderBy('id', 'desc')->paginate(15);
        return view('website_builder.admin.staff.index', compact('staffMembers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:wb_staff,email',
            'password' => 'required|string|min:8',
            'role'     => 'required|string',
        ]);

        WbStaff::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => $request->role,
            'permissions' => $request->permissions ?? ['customers', 'templates'],
            'is_active'   => true,
        ]);

        return redirect()->back()->with('success', 'Staff member added successfully.');
    }

    public function destroy($id)
    {
        $staff = WbStaff::findOrFail($id);
        $staff->delete();

        return redirect()->back()->with('success', 'Staff member deleted.');
    }
}

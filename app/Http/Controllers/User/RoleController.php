<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Validator;

class RoleController extends Controller
{
    public function index()
    {
        $merchantId = Auth::guard('web')->user()->id;
        $data['roles'] = UserRole::where('user_id', $merchantId)->get();
        return view('user.role.index', $data);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'permissions' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $merchantId = Auth::guard('web')->user()->id;

        $role = new UserRole();
        $role->user_id = $merchantId;
        $role->name = $request->name;
        $role->permissions = json_encode($request->permissions);
        $role->save();

        Session::flash('success', __('Staff Role Added Successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function edit($id)
    {
        $merchantId = Auth::guard('web')->user()->id;
        $data['role'] = UserRole::where('user_id', $merchantId)->where('id', $id)->firstOrFail();
        return view('user.role.edit', $data);
    }

    public function update(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'permissions' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()->toArray()
            ], 400);
        }

        $merchantId = Auth::guard('web')->user()->id;

        $role = UserRole::where('user_id', $merchantId)->where('id', $request->role_id)->firstOrFail();
        $role->name = $request->name;
        $role->permissions = json_encode($request->permissions);
        $role->save();

        Session::flash('success', __('Staff Role Updated Successfully'));
        return response()->json(['status' => 'success'], 200);
    }

    public function delete(Request $request)
    {
        $merchantId = Auth::guard('web')->user()->id;
        $role = UserRole::where('user_id', $merchantId)->where('id', $request->role_id)->firstOrFail();

        if ($role->staff()->count() > 0) {
            Session::flash('warning', __('Cannot delete role assigned to active staff members.'));
            return back();
        }

        $role->delete();
        Session::flash('success', __('Role Deleted Successfully'));
        return back();
    }
}

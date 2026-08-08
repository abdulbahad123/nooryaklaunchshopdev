<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class DeleteLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        if (!Auth::guard('admin')->check()) {
            return false;
        }
        $admin = Auth::guard('admin')->user();
        if (!empty($admin->role)) {
            $permissions = json_decode($admin->role->permissions, true);
            return !is_null($permissions) && in_array('Users Management', $permissions);
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:verified_phone_leads,id',
        ];
    }
}

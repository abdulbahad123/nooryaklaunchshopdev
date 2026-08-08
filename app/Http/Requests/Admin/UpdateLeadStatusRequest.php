<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UpdateLeadStatusRequest extends FormRequest
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
        $rules = [
            'id'          => 'required|exists:verified_phone_leads,id',
            'status'      => 'required|string|in:Purchased,Not Purchased,Follow Up,Interested,Not Interested,Converted,Converted / Purchased',
            'status_date' => 'nullable|date',
        ];

        if ($this->status === 'Follow Up') {
            $rules['status_date'] = 'nullable|date|after_or_equal:today';
        }

        return $rules;
    }
}

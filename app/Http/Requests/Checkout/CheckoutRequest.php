<?php

namespace App\Http\Requests\Checkout;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        if (isWebsiteBuilderCheckout($this)) {
            return [
                'customer_email' => 'nullable|email',
                'email'          => 'nullable|email',
                'subdomain'      => 'nullable|max:255',
                'username'       => 'nullable|max:255',
                'password'       => 'nullable',
            ];
        }

        return [
            'first_name' => 'required|max:255',
            // 'last_name' => 'required',
            'shop_name' => 'required|max:255',
            'username' => 'required|max:255',
            'password' => 'required|min:6',
            'email' => 'required|email',
            'country_code' => 'required|max:5',
            'phone' => 'required|regex:/^[0-9]+$/|max:16',
            'city' => 'required|max:255',
            'country' => 'required|max:255',
            'price' => 'required',
            'payment_method' => $this->price != 0 ? 'required' : '',
            'receipt' => $this->is_receipt == 1 ? 'required | mimes:jpeg,jpg,png' : '',
            'stripeToken' => $this->payment_method == 'Stripe' ? 'required' : '',
            'opaqueDataDescriptor' => 'sometimes|required',
            'post_code' => $this->payment_method == 'Iyzico' ? 'required' : '',
            'identity_number' => $this->payment_method == 'Iyzico' ? 'required' : '',
        ];
    }

    public function messages(): array
    {
        return [
            'receipt.required' => 'The receipt field image is required when instruction required receipt image'
        ];
    }

    protected function prepareForValidation(): void
    {
        // 1. If returning from Razorpay or gateway callback, restore session request data
        if (session()->has('wb_checkout_req')) {
            $sess = session('wb_checkout_req');
            if (is_array($sess)) {
                $this->merge(array_merge($sess, $this->all()));
            }
        } elseif (session()->has('data')) {
            $sess = session('data');
            if (is_array($sess)) {
                $this->merge(array_merge($sess, $this->all()));
            }
        } elseif (session()->has('request')) {
            $sess = session('request');
            if (is_array($sess)) {
                $this->merge(array_merge($sess, $this->all()));
            }
        }

        if (isWebsiteBuilderCheckout($this)) {
            $cName  = $this->input('customer_name') ?: ($this->input('first_name') ?: ($this->input('shop_name') ?: 'Agency Owner'));
            $cEmail = $this->input('customer_email') ?: ($this->input('email') ?: '');
            $cPhone = $this->input('customer_phone') ?: ($this->input('phone') ?: '9360157880');
            $cSub   = $this->input('subdomain') ?: ($this->input('username') ?: '');

            $cleanPhone = ltrim(preg_replace('/[^0-9]/', '', (string)$cPhone), '0');
            if (empty($cleanPhone)) $cleanPhone = '9360157880';

            $this->merge([
                'is_website_builder' => 1,
                'customer_name'  => $cName,
                'first_name'      => $this->input('first_name', $cName),
                'shop_name'       => $this->input('shop_name', $cName),
                'customer_email' => $cEmail,
                'email'          => $this->input('email', $cEmail),
                'customer_phone' => $cPhone,
                'phone'          => $cleanPhone,
                'subdomain'      => $cSub,
                'username'       => $this->input('username', $cSub),
                'country_code'   => $this->input('country_code', '+91'),
                'city'           => $this->input('city', 'N/A'),
                'country'        => $this->input('country', 'India'),
                'price'          => $this->input('price', 499),
                'payment_method' => $this->input('payment_method', 'Razorpay'),
                'password'       => $this->input('password', 'Password@123'),
            ]);
        } elseif ($this->has('phone')) {
            $this->merge([
                'phone' => ltrim(preg_replace('/[^0-9]/', '', (string)$this->phone), '0'),
            ]);
        }
    }
}

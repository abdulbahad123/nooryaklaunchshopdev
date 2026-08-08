<?php

namespace App\Http\Requests\Package;

use Illuminate\Foundation\Http\FormRequest;

class PackageUpdateRequest extends FormRequest
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
        $rules =  [
            'title' => 'required|max:255',
            'price' => 'required',
            'term' => 'required',
            'product_limit' => 'required',
            'categories_limit' => 'required',
            'subcategories_limit' => 'required',
            'order_limit' => 'required',
            'language_limit' => 'required',
            'trial_days' => $this->is_trial == "1" ? 'required' : '',
        ];

        $features = $this->features;
        if (!is_null($features)) {
            if (in_array('Blog', $features)) {
                $rules['post_limit'] = 'required';
            }
            if (in_array('Custom Page', $features)) {
                $rules['number_of_custom_page'] = 'required';
            }
            if (in_array('AI Content & Image Generator', $features)) {
                $rules['ai_engine'] = 'required|in:gemini,openai';
                $rules['ai_token_limit'] = 'required|integer|min:0';
                $rules['ai_image_limit'] = 'required|integer|min:0';
            }
        }
        return $rules;
    }
    public function messages(): array
    {
        return [
            'trial_days.required' => __('Trial days is required'),
            // AI Engine
            'ai_engine.required' => __('Please select an AI engine') . '.',
            'ai_engine.in' => __('Selected AI engine must be either Gemini or OpenAI'),

            // AI Token Limit
            'ai_token_limit.required' => __('AI token limit is required when AI feature is enabled'),
            'ai_token_limit.integer' => __('AI token limit must be a valid number'),
            'ai_token_limit.min' => __('AI token limit cannot be negative'),

            // AI Image Limit
            'ai_image_limit.required' => __('AI image limit is required when AI feature is enabled'),
            'ai_image_limit.integer' => __('AI image limit must be a valid number'),
            'ai_image_limit.min' => __('AI image limit cannot be negative'),
        ];
    }
}

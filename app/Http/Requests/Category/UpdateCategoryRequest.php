<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
//            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'integer|exists:services,id',
            'order' => 'nullable|integer|min:-128|max:127'
        ];
    }
}

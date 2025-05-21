<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetProductsRequestInfoRequest extends FormRequest
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
            'category_id' => 'nullable|array',
            'category_id.*' => 'integer',
            'subcategory_id' => 'nullable|array',
            'subcategory_id.*' => 'integer',
            'provider_id' => 'nullable|array',
            'provider_id.*' => 'integer',
            'type' => 'nullable|string',
            'search' => 'nullable|string|max:500',
        ];
    }
}

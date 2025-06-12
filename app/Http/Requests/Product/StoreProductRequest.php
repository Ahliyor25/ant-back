<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sku' => 'nullable|string|max:255|unique:products,sku',
            'provider_id' => 'required|integer|exists:providers,id',
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'collection_id' => 'nullable|exists:collections,id',
            'base_price' => 'required|numeric',
            'unit' => 'nullable|string|max:255',
            'discount' => 'nullable|numeric|min:0|max:100',
            'quantity' => 'required|integer|min:0',
            'product_type_id' => 'nullable|integer|exists:product_types,id',
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'integer|exists:services,id',
        ];
    }
}

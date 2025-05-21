<?php

namespace App\Http\Requests\Product\ProductAttribute;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductAttribute extends FormRequest
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
            'product_id' => 'required|integer|exists:products,id',
            'attribute_id' => 'required|integer|exists:attributes,id',
            'value' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
            'sku' => 'required|unique:product_attributes,sku',
        ];
    }
}

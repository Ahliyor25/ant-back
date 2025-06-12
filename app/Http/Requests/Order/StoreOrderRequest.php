<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'payment_method_id' => 'required|integer|exists:payment_methods,id',
            'comment' => 'nullable|string',
            'shipping_address' => 'nullable|string|max:255',
            'shipping_type_id' => 'nullable|exists:shipping_types,id',
            'shipping_location_id' => 'nullable|exists:shipping_locations,id',
            'products.*.product_id' => 'required|integer|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.product_attributes.*.product_attribute_id' => 'nullable|integer|exists:product_attributes,id',
            'products.*.services.*.service_id' => 'nullable|integer|exists:services,id',
            'products.*.services.*.service_value' => 'nullable|numeric|min:0',
            'products.*.services.*.service_quantity' => 'nullable|integer|min:0',
        ];
    }
}

<?php

namespace App\Http\Requests\ShippingType;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShippingTypeRequest extends FormRequest
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
            'key' => 'required|string|max:255|unique:shipping_types,key,' . $this->route('shippingType')->id,
            'name' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
//            'price' => 'nullable|numeric|min:0',
            'service_id' => 'required|int|exists:services,id',
            'is_active' => 'required|boolean',
        ];
    }
}

<?php

namespace App\Http\Requests\ShippingLocation;

use Illuminate\Foundation\Http\FormRequest;

class StoreShippingLocationRequest extends FormRequest
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
//            'price' => 'required|numeric|min:0',
            'is_active' => 'required|boolean',
            'service_id' => 'required|int|exists:services,id',
            'order' => 'nullable|integer|min:-128|max:127',
        ];
    }
}

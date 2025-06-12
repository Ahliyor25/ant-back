<?php

namespace App\Http\Requests\AdvertisingRate;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdvertisingRateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

            return [
                'service_type' => 'nullable|string',
                'tariff_name' => 'required|string',
                'discount_percent' => 'nullable|integer',
                'duration' => 'nullable|string',
                'show_count' => 'nullable|integer',
                'price' => 'required|numeric',
                'show_period' => 'nullable|string',
                'daily_shows' => 'nullable|integer',
                'language_id' => 'required|exists:languages,id',
            ];
    }
}

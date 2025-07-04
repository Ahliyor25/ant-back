<?php

namespace App\Http\Requests\Showroom;

use Illuminate\Foundation\Http\FormRequest;

class StoreShowroomRequest extends FormRequest
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
            'image' => 'required|image|max:512',
            'address' => 'required|string|max:255',
            'phones' => 'nullable|string',
        ];
    }
}

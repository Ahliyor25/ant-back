<?php

namespace App\Http\Requests\PackageAttr;

use Illuminate\Foundation\Http\FormRequest;

class StorePackageAttr extends FormRequest
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
            'package_id' => 'required|exists:packages,id',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4048',
            'text' => 'required|string',
            'order' => 'nullable|integer',
        ];
    }
}

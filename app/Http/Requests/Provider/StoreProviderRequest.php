<?php

namespace App\Http\Requests\Provider;

use Illuminate\Foundation\Http\FormRequest;

class StoreProviderRequest extends FormRequest
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
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "logo" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'integer|exists:categories,id',
            'subcategory_ids' => 'nullable|array',
            'subcategory_ids.*' => 'integer|exists:subcategories,id',
            "file" => "nullable|file|mimes:jpg,png,jpeg,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar|max:131072"
        ];
    }
}

<?php

namespace App\Http\Requests\ProjectImage;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectImageRequest extends FormRequest
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
            'title' => 'nullable|string|max:255',
            'image' => 'required|image|mimes:jpeg,webp,png,jpg,gif,svg|max:2048',
            'project_id' => 'required|integer|exists:projects,id'
        ];
    }
}

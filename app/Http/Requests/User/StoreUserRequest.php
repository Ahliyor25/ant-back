<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'email' => 'required_if:phone,null|string|email|max:255|unique:users',
            'phone' => 'required_if:email,null|string|max:255|unique:users',
            'address' => 'nullable|string|max:255',
            'password' => 'required|string|min:6',
            'role_ids'   => 'required|array',
            'role_ids.*'   => 'integer|exists:roles,id',
        ];
    }
}

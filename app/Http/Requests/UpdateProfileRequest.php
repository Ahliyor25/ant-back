<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
//            'email' => 'required_if:phone,null|email|unique:users,email,' . auth()->user()->id,
            'phone' => 'required|string|unique:users,phone,' . auth()->user()->id,
            'address' => 'nullable|string|max:255',
            'otp' => 'nullable|integer|digits:4',
        ];
    }
}


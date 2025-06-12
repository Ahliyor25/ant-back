<?php

namespace App\Http\Requests\PromotionWinner;

use Illuminate\Foundation\Http\FormRequest;

class StorePromotionWinnerRequest extends FormRequest
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
            'promotion_id' => 'required|exists:promotions,id',
            'promotion_draw_id' => 'nullable|exists:promotion_draws,id',
            'promotion_prize_id' => 'nullable|exists:promotion_prizes,id',
            'region_id' => 'required|exists:regions,id',
            'full_name' => 'nullable|string|max:255',
            'month' => 'nullable|string|max:255',
            'prize_type' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4048',
        ];
    }
}

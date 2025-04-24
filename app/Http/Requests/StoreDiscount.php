<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscount extends FormRequest
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
        $discount_id = $this->route('id');
        return [
            'code' => "required|integer|max_digits:5|unique:discounts,code,$discount_id,id",
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_time',
            'price' => 'required|integer'
        ];
    }
}

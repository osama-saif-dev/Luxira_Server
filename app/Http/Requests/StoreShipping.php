<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipping extends FormRequest
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
        $shipping_id = $this->route('id');
        return [
            'city_en' => "required|string|unique:shippings,city->en,$shipping_id,id",
            'city_ar' => "required|string|unique:shippings,city->ar,$shipping_id,id",
            'price' => 'required|integer'
        ];
    }
}

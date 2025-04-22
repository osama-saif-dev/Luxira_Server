<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProduct extends FormRequest
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
            'name' => 'required|string',
            'price' => 'required|decimal:10,2',
            'quantity' => 'required|integer|min:1',
            'desc' => 'required|string',
            'status' => 'nullable|in:active,un_active',
            'brand_id' => 'required|integer',
            'subcategory_id' => 'required|integer',
        ];
    }
}

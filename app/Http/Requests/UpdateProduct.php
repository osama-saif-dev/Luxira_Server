<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProduct extends FormRequest
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
        $product_id = $this->route('id');
        return [
            'name_en' => "required|string|unique:products,name->en,$product_id,id",
            'name_ar' => "required|string|unique:products,name->ar,$product_id,id",
            'price' => 'required|numeric',
            'quantity' => 'required|integer|min:1',
            'desc_en' => 'required|string',
            'desc_ar' => 'required|string',
            'status' => 'required|in:active,un_active',
            'brand_id' => 'required|integer|exists:brands,id',
            'size_id' => 'required|array',
            'size_id.*' => 'required|integer|exists:sizes,id',
            'color_id' => 'required|array',
            'color_id.*' => 'required|integer|exists:colors,id',
            'subcategory_id' => 'required|integer|exists:subcategories,id',
            'image' => 'nullable|array',
            'image.*' => 'nullable|file|mimes:png,jpg,jpeg|max:3072'
        ];
    }
}

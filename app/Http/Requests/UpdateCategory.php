<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategory extends FormRequest
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
        $category_id = $this->route('id');
        return [
            'name_en' => "required|string|unique:brands,name->en,$category_id,id",
            'name_ar' => "required|string|unique:brands,name->ar,$category_id,id",
            'image' => 'nullable|file|mimes:png,jpg,jpeg|max:3072',
            'status' => 'required|string|in:active,un_active'
        ];
    }
}

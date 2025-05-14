<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:categories,name|max:255',
            'code' => 'required|unique:categories,code|max:255',
            'description' => 'nullable',
            // 'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'brand_id' => 'required|exists:brands,id',
        ];
    }
}

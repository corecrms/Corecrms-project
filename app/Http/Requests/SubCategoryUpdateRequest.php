<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubCategoryUpdateRequest extends FormRequest
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

            'category_id' => 'required|exists:categories,id',
            'name' => 'required|unique:sub_categories,name,' . $this->sub_category->id . '|max:255',
            'code' => 'required|unique:sub_categories,code,' . $this->sub_category->id . '|max:255',
            'description' => 'nullable',
        ];
    }
}

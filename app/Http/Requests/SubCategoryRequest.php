<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubCategoryRequest extends FormRequest
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
        $subCategoryId = $this->route('sub_category') ? $this->route('sub_category')->id : null;

        if ($this->isMethod('post')) {
            return [
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|unique:sub_categories,name|max:255',
                'code' => 'required|unique:sub_categories,code|max:255',
                'description' => 'nullable',
            ];
        } elseif ($this->isMethod('put')) {
            return [
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|unique:sub_categories,name,' . $subCategoryId . '|max:255',
                'code' => 'required|unique:sub_categories,code,' . $subCategoryId . '|max:255',
                'description' => 'nullable',
            ];
        }

        return [];
    }
}

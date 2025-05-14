<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
                'name' => 'required',
                'email' => 'required|email|max:255|unique:users,email,' . $userId,
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'contact_no' => 'nullable|numeric',
                'address' => 'nullable',
                'password' => 'required|min:8',
                'status' => 'nullable',
                'company_name' => 'nullable',
        ];
    }
}

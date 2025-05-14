<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseUpdateRequest extends FormRequest
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
            'date' => 'required|date',
            'vendor_id' => 'required|integer|exists:vendors,id',
            'ntn_no' => 'nullable',
            'order_tax' => 'required|numeric',
            'discount' => 'required|numeric',
            'shipping' => 'required|numeric',
            'status' => 'required',
            'payment_status' => 'required',
            'payment_method' => 'nullable',
            'amount_received' => 'nullable|numeric',
            'amount_pay' => 'nullable|numeric',
            'notes' => 'nullable',
            'grand_total' => 'required|numeric',

        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseStoreRequest extends FormRequest
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
            // 'reference' => 'required',
            'date' => 'required|date',
            'vendor_id' => 'required|integer|exists:vendors,id',
            // 'ntn_no' => 'required',
            'order_tax' => 'required|numeric',
            'discount' => 'required|numeric',
            'shipping' => 'required|numeric',
            'status' => 'required',
            'payment_status' => 'required',
            'payment_method' => 'nullable',
            'amount_received' => 'nullable|numeric',
            'amount_pay' => 'nullable|numeric',
            'change_return' => 'nullable|numeric',
            'notes' => 'nullable',
            'grand_total' => 'required|numeric',
            'bank_account' => 'nullable',
            'amount_due' => 'nullable|numeric',
        ];
    }
}


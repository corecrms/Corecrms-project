<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
        // dd($this->route('product'));
        $productId = $this->route('product') ? $this->route('product') : null;
        return [
            'name' => 'sometimes|required|string|max:255',
            'barcode' => 'sometimes|required',
            'productCode' => 'sometimes|required|string|max:255|unique:products,sku,' . $productId,
            'description' => 'nullable|string',
            'category_id' => 'sometimes|required|integer|exists:categories,id',
            'sub_category_id' => 'sometimes|required|integer|exists:sub_categories,id',
            'brand_id' => 'sometimes|required|integer|exists:brands,id',
            'img.*' => 'nullable|image',
            'tax_type' => 'sometimes|required',
            'purchase_price' => 'nullable|numeric',
            'sell_price' => 'nullable|numeric',
            'product_unit' => 'nullable|integer|exists:units,id',
            'sale_unit' => 'nullable|integer|exists:units,id',
            'purchase_unit' => 'nullable|integer|exists:units,id',
            'stock_alert' => 'nullable|numeric',
            'order_tax' => 'nullable|numeric',
            'status' => 'nullable|integer',
            'imei_no' => 'nullable|string|max:255',
            'product_type' => 'sometimes|required|string|max:255',
            'warehouse_id' => 'sometimes|required|integer|exists:warehouses,id',
            'sku' => 'required|string|max:255|unique:products,sku,' . $productId,

            'product_weight_unit' => 'required|string|max:255',
            'product_weight' => 'required|string|max:255',
            'product_dimension_unit' => 'required|string|max:255',
            'product_length' => 'required|string|max:255',
            'product_width' => 'required|string|max:255',
            'product_height' => 'required|string|max:255',
            'product_imei_no' => 'nullable|string|max:255',
            'ailse' => 'nullable|string|max:255',
            'rack' => 'nullable|string|max:255',
            'shelf' => 'nullable|string|max:255',
            'bin' => 'nullable|string|max:255',

        ];
    }
}

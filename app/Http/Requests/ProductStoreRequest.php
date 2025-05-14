<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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

        $productId = $this->route('product') ? $this->route('product')->id : null;
        // dd($this);
        return [
            'name' => 'required|string|max:255',
            'barcode' => 'required',
            'productCode' => 'required|string|max:255|unique:products,sku,' . $productId,
            'description' => 'nullable|string',
            'category_id' => 'required|integer|exists:categories,id',
            'sub_category_id' => 'required|integer|exists:sub_categories,id',
            'brand_id' => 'required|integer|exists:brands,id',
            'img.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'img' => 'required|array',
            'tax_type' => 'required',
            'purchase_price' => 'nullable|numeric',
            'sell_price' => 'nullable|numeric',
            'product_unit' => 'nullable|integer|exists:units,id',
            'sale_unit' => 'nullable|integer|exists:units,id',
            'purchase_unit' => 'nullable|integer|exists:units,id',
            'stock_alert' => 'nullable|numeric',
            'order_tax' => 'nullable|numeric',
            'status' => 'nullable|integer',
            'imei_no' => 'nullable|string|max:255',
            'product_type' => 'required|string|max:255',
            'warehouse_id' => 'required|exists:warehouses,id',
            'product_live' => 'nullable|string',
            'new_product' => 'nullable|string',
            'featured_product' => 'nullable|string',
            'best_seller' => 'nullable|string',
            'recommended' => 'nullable|string',

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

    public function messages()
    {
        return [
            'name.required' => 'The product name is required.',
            'barcode.required' => 'The barcode is required.',
            'productCode.required' => 'The product code is required.',
            'productCode.unique' => 'The product code must be unique.',
            'description.string' => 'The description must be a string.',
            'category_id.required' => 'Please select a category.',
            'category_id.integer' => 'Please select a category.',
            'brand_id.required' => 'Please select a brand.',
            'brand_id.integer' => 'Please select a brand.',
            'img.required' => 'Please select an image.',
            'img.*.image' => 'The image must be an image file.',
            'img.*.mimes' => 'The image must be of type: jpeg, png, jpg, gif, or svg.',
            'tax_type.required' => 'The tax type is required.',
            'purchase_price.numeric' => 'The purchase price must be a number.',
            'sell_price.numeric' => 'The sell price must be a number.',
            'product_unit.integer' => 'Invalid product unit.',
            'sale_unit.integer' => 'Invalid sale unit.',
            'purchase_unit.integer' => 'Invalid purchase unit.',
            'stock_alert.numeric' => 'The stock alert must be a number.',
            'order_tax.numeric' => 'The order tax must be a number.',
            'imei_no.string' => 'The IMEI number must be a string.',
            'product_type.required' => 'The product type is required.',
            'warehouse_id.required' => 'Please select a warehouse.',
            'warehouse_id.exists' => 'Warehouse does not exists.',

            'product_weight_unit.required' => 'The product weight unit is required.',
            'product_weight.required' => 'The product weight is required.',
            'product_dimension_unit.required' => 'The product dimension unit is required.',
            'product_length.required' => 'The product length is required.',
            'product_width.required' => 'The product width is required.',
            'product_height.required' => 'The product height is required.',

        ];
    }

}

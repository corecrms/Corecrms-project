<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
                // dd(request()->all());

        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'description' => $this->description,
            'unit_id' => $this->unit_id,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'barcode' => $this->barcode,
            // 'quantity' => $this->quantity,
            'warranty_id' => $this->warranty_id,
            'condition' => $this->condition,
            'tax_applicable' => $this->tax_applicable,
            'tax_info' => $this->tax_info,
            'purchase_price' => $this->purchase_price,
            'sell_price' => $this->sell_price,
            'shopify_id' => $this->shopify_id,
            // 'alert_quantity' => $this->alert_quantity,
            // 'business_location' => $this->business_location,
            // 'manage_stock' => $this->manage_stock,
            // 'product_variant' => $this->product_variant,
            // 'unit_cost' => $this->unit_cost,
            // 'price_ex_tax' => $this->price_ex_tax,
            // 'unit_cost_inc_tax' => $this->unit_cost_inc_tax,
            // 'profit_margin' => $this->profit_margin,
            // 'product_tax' => $this->product_tax,
            // 'product_type' => $this->product_type,
            // 'product_weight' => $this->product_weight,
            // 'custom_field_1' => $this->custom_field_1,
            // 'custom_field_2' => $this->custom_field_2,
            // 'custom_field_3' => $this->custom_field_3,
            'created_at' => $this->created_at,
        	'updated_at' => $this->updated_at,

        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SalesInvoiceDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
                
                'id' => $this->id,
                'sales_invoice_id' => $this->sales_invoice_id,
                'product_id' => $this->product_id,
                'quantity' => $this->quantity,
                'unit_price' => $this->unit_price,
                'discount' => $this->discount,
                'tax' => $this->tax,
                'total' => $this->total,
                'created_by' => $this->created_by,
                'updated_by' => $this->updated_by,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
    
                // load relation
                'product' => new ProductResource($this->whenLoaded('product')),
                'salesInvoice' => new SalesInvoiceResource($this->whenLoaded('salesInvoice')),  
        ];
    }
}

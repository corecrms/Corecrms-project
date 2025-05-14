<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;


class PurchaseResource extends JsonResource
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
            'date' => $this->date,
            'product' => [
                'id' => $this->product->id,
                'sku' => $this->product->sku,
                'product_type' => $this->product->product_type,
            ],
            'vendor' => [
                'id' => $this->vendor->id,
                'name' => $this->vendor->name,
                'email' => $this->vendor->email,
            ],
            'discount_id' => $this->discount_id,
            'ntn' => $this->ntn,
            'order_tax' => $this->order_tax,
            'discount' => $this->discount,
            'shipping' => $this->shipping,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'amount_recieved' => $this->amount_recieved,
            'amount_pay' => $this->amount_pay,
            'change_return' => $this->change_return,
            'bank_account' => $this->bank_account,
            'grand_total' => $this->grand_total,
            'notes' => $this->notes,

        ];
    }
}

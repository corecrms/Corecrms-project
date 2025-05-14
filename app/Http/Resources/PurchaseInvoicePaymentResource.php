<?php

namespace App\Http\Resources;

use App\Http\Resources\VendorResource;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\SalesInvoiceDetailResource;
use App\Http\Resources\SalesInvoicePaymentResource;
use App\Http\Resources\SalesInvoiceCreditNotesResource;

class PurchaseInvoicePaymentResource extends JsonResource
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
            'customer_id' => $this->customer_id,
            'vendor_id' => $this->vendor_id,
            'invoice_number' => $this->invoice_number,
            'reference_number' => $this->reference_number,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'gross_amount' => $this->gross_amount,
            'discount' => $this->discount,
            'net_amount' => $this->net_amount,
            'paid_amount' => $this->paid_amount,
            'status' => $this->status,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // load relation
            // 'customer' => new CustomerResource($this->whenLoaded('customer')),
            // 'vendor' => new VendorResource($this->whenLoaded('vendor')),

        ];
    }
}

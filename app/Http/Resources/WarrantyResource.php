<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WarrantyResource extends JsonResource
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
        return [
        	'id' => $this->id,
        	'warranty_name' => $this->warranty_name,
        	'warranty_type' => $this->warranty_type,
            'warranty_period' => $this->warranty_period,
            'warranty_description' => $this->warranty_description,
        	'created_at' => $this->created_at,
        	'updated_at' => $this->updated_at,
        ];
    }
}

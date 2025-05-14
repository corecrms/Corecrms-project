<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'tax_number' => $this->tax_number,
            'status' => $this->status,
            'password' => Hash::make("123456"),
            'created_at' => $this->created_at,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                // Add any other user attributes you want to include
            ],
            'tier' => [
                'id' => $this->tier->id,
                'name' => $this->tier->name,
                'discount' => $this->tier->discount,
                // Add any other tier attributes you want to include
            ],
        ];
    }
}

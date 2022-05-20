<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'due_on' => $this->due_on,
            'amount' => $this->amount,
            'vat' => $this->vat,
            'is_vat_inclusive' => $this->is_vat_inclusive,
            'status' => isset($this->status) ? $this->status : "",
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'payer' => $this->user,
            'category' => $this->category,
            'sub_category' => isset($this->subCategory) ? $this->subCategory : (object)[],
            'payments' => isset($this->payments) ? $this->payments : [],
        ];
    }
}

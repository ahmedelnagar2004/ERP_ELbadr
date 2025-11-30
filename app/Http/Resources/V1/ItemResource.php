<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
             'id' => $this->id,
            'name' => $this->name,
            'code' => $this->item_code,
            'price' => $this->price,
            'is_shown_in_store' => $this->is_shown_in_store,
            'description' => $this->description,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WishlistResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'      => $this->id,
            'user_id' => $this->user_id,
            'car_id'  => $this->car_id,
            'created_at' => $this->created_at,
            'car'     => new CarResource($this->whenLoaded('car')),
        ];
    }
}

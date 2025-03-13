<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'user_id' => $this->user_id,
            'car_id' => $this->car_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'proof_of_payment' => $this->proof_of_payment,
            'payment_status' => $this->payment_status,
            'status' => $this->status
        ];
    }
}

//RESOURCE INI GAJADI DIPAKE

// class UserResource extends JsonResource
// {
//     public function toArray($request)
//     {
//         return [
//            'id' => $this->id,
//             'name' => $this->name,
//             'email' => $this->email,
//         ];
//     }
// }

// class CarResource extends JsonResource
// {
//     public function toArray($request)
//     {
//         return [
//             'id' => $this->id,
//             'name' => $this->name,
//             'brand_name' => $this->brand_name,
//             'price_per_day' => $this->price_per_day,
//             'stock' => $this->stock,
//         ];
//     }
// }

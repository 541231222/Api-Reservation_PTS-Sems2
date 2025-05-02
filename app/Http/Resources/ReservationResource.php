<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="ReservationResource",
 *     type="object",
 *     title="Reservation Resource",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user", type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="John Doe"),
 *         @OA\Property(property="email", type="string", example="john@example.com")
 *     ),
 *     @OA\Property(property="car", type="object",
 *         @OA\Property(property="id", type="integer", example=10),
 *         @OA\Property(property="name", type="string", example="Avanza"),
 *         @OA\Property(property="brand_name", type="string", example="Toyota"),
 *         @OA\Property(property="category_id", type="integer", example=3),
 *         @OA\Property(property="image", type="string", example="avanza.jpg"),
 *         @OA\Property(property="price_per_day", type="integer", example=250000),
 *         @OA\Property(property="stock", type="integer", example=5)
 *     ),
 *     @OA\Property(property="start_date", type="string", format="date", example="2025-05-01"),
 *     @OA\Property(property="end_date", type="string", format="date", example="2025-05-05"),
 *     @OA\Property(property="proof_of_payment", type="string", example="bukti.jpg"),
 *     @OA\Property(property="payment_status", type="string", example="paid"),
 *     @OA\Property(property="status", type="string", example="confirmed"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-01T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-01T12:00:00Z")
 * )
 */
class ReservationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? null,
                'email' => $this->user->email ?? null, // Tambahan jika diperlukan
            ],
            'car' => [
                'id' => $this->car->id ?? null,
                'name' => $this->car->name ?? null,
                'brand_name' => $this->car->brand_name ?? null,
                'category_id' => $this->car->category_id ?? null,
                'image' => $this->car->image ?? null,
                'price_per_day' => $this->car->price_per_day ?? null,
                'stock' => $this->car->stock ?? null,
            ],
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'proof_of_payment' => $this->proof_of_payment,
            'payment_status' => $this->payment_status,
            'status' => $this->status,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Http\Requests\ReservationRequest;
use App\Http\Requests\UpdateReservationStatusRequest;
use App\Http\Resources\ReservationResource;
use Illuminate\Http\Request;

/**
 * 
 * @OA\Post(
 *     path="/reservation",
 *     tags={"Reservation"},
 *     summary="Create a new reservation",
 *     description="Store a new reservation for a user and car",
 *     operationId="reserveCar",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "car_id", "start_date", "end_date", "proof_of_payment", "payment_status", "status"},
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="car_id", type="integer", example=1),
 *             @OA\Property(property="start_date", type="string", format="date", example="2025-05-01"),
 *             @OA\Property(property="end_date", type="string", format="date", example="2025-05-05"),
 *             @OA\Property(property="proof_of_payment", type="string", example="payment.jpg"),
 *             @OA\Property(property="payment_status", type="string", example="paid"),
 *             @OA\Property(property="status", type="string", example="confirmed")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Reservation successfully created",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/ReservationResource")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error while creating reservation"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/reservation/{id}",
 *     tags={"Reservation"},
 *     summary="Get a reservation by ID",
 *     description="Retrieve details of a reservation including user and car information",
 *     operationId="getReservation",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Reservation details retrieved successfully",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/ReservationResource")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Reservation not found"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/reservation/{id}",
 *     tags={"Reservation"},
 *     summary="Update reservation status",
 *     description="Update the payment status and status of the reservation",
 *     operationId="updateReservationStatus",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"payment_status", "status"},
 *             @OA\Property(property="payment_status", type="string", example="paid"),
 *             @OA\Property(property="status", type="string", example="confirmed")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Reservation status updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Reservation not found"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/reservation/{id}",
 *     tags={"Reservation"},
 *     summary="Delete a reservation",
 *     description="Delete a reservation by its ID",
 *     operationId="deleteReservation",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Reservation successfully deleted"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Reservation not found"
 *     )
 * )
 */
class ReservationSwaggerController extends Controller
{
    public function reserves(ReservationRequest $request)
    {
        try {
            $data = $request->validated();

            $reserve = Reservation::create([
                'user_id' => $data['user_id'],
                'car_id' => $data['car_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'proof_of_payment' => $data['proof_of_payment'],
                'payment_status' => $data['payment_status'],
                'status' => $data['status']
            ]);

            return response()->json([
                "messages" => "Pemesanan Berhasil Dibuat",
                "reservation" => new ReservationResource($reserve)
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getReservation($id)
    {
        $reservation = Reservation::with(['user', 'car'])->find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        $data = [
            'user' => [
                'id' => $reservation->user->id,
                'name' => $reservation->user->name,
            ],
            'car' => [
                'id' => $reservation->car->id,
                'name' => $reservation->car->name,
                'brand_name' => $reservation->car->brand_name
            ],
            'reservation' => [
                'id' => $reservation->id,
                'start_date' => $reservation->start_date,
                'end_date' => $reservation->end_date,
                'proof_of_payment' => $reservation->proof_of_payment,
                'payment_status' => $reservation->payment_status,
                'status' => $reservation->status,
            ],
        ];

        return response()->json($data, 200);
    }

    public function update(UpdateReservationStatusRequest $request, $id)
    {
        try {
            $reservation = Reservation::find($id);

            if (!$reservation) {
                return response()->json(['error' => 'Reservasi tidak ditemukan'], 404);
            }

            $reservation->update([
                'payment_status' => $request->payment_status,
                'status' => $request->status
            ]);

            return response()->json([
                'message' => 'Status pemesanan berhasil diperbarui',
                'reservation' => new ReservationResource($reservation->load(['car', 'user']))
            ], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $reservation = Reservation::find($id);

            if (!$reservation) {
                return response()->json(['error' => 'Reservasi tidak ditemukan'], 404);
            }

            $reservation->delete();

            return response()->json(['message' => 'Reservasi berhasil dihapus'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

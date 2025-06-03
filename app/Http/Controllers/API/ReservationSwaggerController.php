<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use App\Http\Requests\UpdateReservationStatusRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Request;

class ReservationSwaggerController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/reservation",
     *     summary="Buat reservasi mobil",
     *     tags={"Reservation"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="Accept",
     *         in="header",
     *         required=true,
     *         description="Response format",
     *         @OA\Schema(type="string", default="application/json")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "car_id", "start_date", "end_date", "proof_of_payment", "payment_status", "status"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="car_id", type="integer", example=2),
     *             @OA\Property(property="start_date", type="string", format="date", example="2025-06-01"),
     *             @OA\Property(property="end_date", type="string", format="date", example="2025-06-05"),
     *             @OA\Property(property="proof_of_payment", type="string", example="bukti-transfer.jpg"),
     *             @OA\Property(property="payment_status", type="string", example="paid"),
     *             @OA\Property(property="status", type="string", example="pending")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Pemesanan Berhasil Dibuat"),
     *     @OA\Response(response=500, description="Kesalahan server")
     * )
     */
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
            return response()->json(["messages" => "Pemesanan Berhasil Dibuat", "reservation" => new ReservationResource($reserve)], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/reservation/{id}",
     *     summary="Ambil detail reservasi berdasarkan ID",
     *     tags={"Reservation"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Detail reservasi ditemukan"),
     *     @OA\Response(response=404, description="Reservasi tidak ditemukan")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/reservation",
     *     summary="Ambil semua reservasi dengan filter opsional",
     *     tags={"Reservation"},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="Filter berdasarkan status reservasi",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="payment_status",
     *         in="query",
     *         required=false,
     *         description="Filter berdasarkan status pembayaran",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Data reservasi berhasil diambil"),
     *     @OA\Response(response=404, description="Tidak ada data reservasi ditemukan")
     * )
     */
    public function getAllReservations(ReservationRequest $request)
    {
        $query = Reservation::with(['user', 'car']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $reservations = $query->get();

        if ($reservations->isEmpty()) {
            return response()->json(['message' => 'Tidak ada data reservasi ditemukan.'], 404);
        }

        return response()->json([
            'message' => 'Data reservasi berhasil diambil.',
            'data' => ReservationResource::collection($reservations)
        ], 200);
    }


    /**
     * @OA\Put(
     *     path="/api/reservation/{id}",
     *     summary="Perbarui status reservasi",
     *     tags={"Reservation"},
     *     security={{"bearerAuth":{}}},
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
     *     @OA\Response(response=200, description="Status pemesanan berhasil diperbarui"),
     *     @OA\Response(response=404, description="Reservasi tidak ditemukan"),
     *     @OA\Response(response=500, description="Kesalahan server")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/reservation/{id}",
     *     summary="Hapus reservasi",
     *     tags={"Reservation"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Reservasi berhasil dihapus"),
     *     @OA\Response(response=404, description="Reservasi tidak ditemukan"),
     *     @OA\Response(response=500, description="Kesalahan server")
     * )
     */
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

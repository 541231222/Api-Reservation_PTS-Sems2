<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReservationRequest;
use App\Http\Requests\UpdateReservationStatusRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
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
            return response()->json(["messages" => "Pemesanan Berhasil Dibuat", "reservation" => new ReservationResource($reserve)], 200);
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

        return response()->json($data,200);

    }
    //kalo ngambil 1 data berarti ....resource::new kalo banyak collection

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

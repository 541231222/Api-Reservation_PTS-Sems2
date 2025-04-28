<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 *     path="/reservation",
 *     tags={"Reservation"},
 *     operationId="listReservation",
 *     summary="List of Reservations",
 *     description="Retrieve a list of reservations",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             example={
 *                 "success": true,
 *                 "message": "Successfully retrieved reservations",
 *                 "data": {
 *                     {
 *                         "id": 1,
 *                         "user_id": 5,
 *                         "car_id": 2,
 *                         "start_date": "2025-05-01",
 *                         "end_date": "2025-05-05",
 *                         "proof_of_payment": "payment1.jpg",
 *                         "payment_status": "Paid",
 *                         "status": "Confirmed"
 *                     },
 *                     {
 *                         "id": 2,
 *                         "user_id": 8,
 *                         "car_id": 3,
 *                         "start_date": "2025-06-10",
 *                         "end_date": "2025-06-12",
 *                         "proof_of_payment": "payment2.jpg",
 *                         "payment_status": "Pending",
 *                         "status": "Pending Confirmation"
 *                     }
 *                 }
 *             }
 *         )
 *     )
 * )
 */
class ReservationSwaggerController extends Controller
{
    public function listReservation()
    {
        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved reservations',
            'data' => [
                [
                    'id' => 1,
                    'user_id' => 5,
                    'car_id' => 2,
                    'start_date' => '2025-05-01',
                    'end_date' => '2025-05-05',
                    'proof_of_payment' => 'payment1.jpg',
                    'payment_status' => 'Paid',
                    'status' => 'Confirmed'
                ],
                [
                    'id' => 2,
                    'user_id' => 8,
                    'car_id' => 3,
                    'start_date' => '2025-06-10',
                    'end_date' => '2025-06-12',
                    'proof_of_payment' => 'payment2.jpg',
                    'payment_status' => 'Pending',
                    'status' => 'Pending Confirmation'
                ]
            ]
        ]);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 *     path="/car",
 *     tags={"Car"},
 *     operationId="listCar",
 *     summary="List of Cars",
 *     description="Retrieve a list of cars",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             example={
 *                 "success": true,
 *                 "message": "Successfully retrieved cars",
 *                 "data": {
 *                     {
 *                         "id": 1,
 *                         "user_id": 5,
 *                         "category_id": 2,
 *                         "name": "Toyota Fortuner",
 *                         "image": "fortuner.jpg",
 *                         "brand_name": "Toyota",
 *                         "price_per_day": 500000,
 *                         "stock": 3
 *                     },
 *                     {
 *                         "id": 2,
 *                         "user_id": 8,
 *                         "category_id": 1,
 *                         "name": "Honda Jazz",
 *                         "image": "jazz.jpg",
 *                         "brand_name": "Honda",
 *                         "price_per_day": 350000,
 *                         "stock": 5
 *                     }
 *                 }
 *             }
 *         )
 *     )
 * )
 */
class CarSwaggerController extends Controller
{
    public function listCar()
    {
        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved cars',
            'data' => [
                [
                    'id' => 1,
                    'user_id' => 5,
                    'category_id' => 2,
                    'name' => 'Toyota Fortuner',
                    'image' => 'fortuner.jpg',
                    'brand_name' => 'Toyota',
                    'price_per_day' => 500000,
                    'stock' => 3
                ],
                [
                    'id' => 2,
                    'user_id' => 8,
                    'category_id' => 1,
                    'name' => 'Honda Jazz',
                    'image' => 'jazz.jpg',
                    'brand_name' => 'Honda',
                    'price_per_day' => 350000,
                    'stock' => 5
                ]
            ]
        ]);
    }
}

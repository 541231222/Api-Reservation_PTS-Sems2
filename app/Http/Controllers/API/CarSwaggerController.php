<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Resources\CarResource;
use App\Models\Car;
use Illuminate\Http\Request;

/**
 * @OA\Post(
 *     path="/car",
 *     tags={"Car"},
 *     summary="Add a new Car",
 *     description="Store a new car to database",
 *     operationId="storeCar",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "category_id", "name", "image", "brand_name", "price_per_day", "stock"},
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="category_id", type="integer", example=2),
 *             @OA\Property(property="name", type="string", example="Toyota Fortuner"),
 *             @OA\Property(property="image", type="string", example="fortuner.jpg"),
 *             @OA\Property(property="brand_name", type="string", example="Toyota"),
 *             @OA\Property(property="price_per_day", type="integer", example=500000),
 *             @OA\Property(property="stock", type="integer", example=5),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Mobil berhasil ditambahkan"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Terjadi kesalahan saat menambahkan mobil"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/car",
 *     tags={"Car"},
 *     summary="Get all Cars",
 *     description="Retrieve a list of all cars",
 *     operationId="getAllCars",
 *     @OA\Response(
 *         response=200,
 *         description="List of cars retrieved successfully"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/car/{id}",
 *     tags={"Car"},
 *     summary="Delete a Car",
 *     description="Delete a car by its ID",
 *     operationId="deleteCar",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of car to delete",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Mobil berhasil dihapus"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Mobil tidak ditemukan"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/car/{id}",
 *     tags={"Car"},
 *     summary="Update a Car",
 *     description="Update car information",
 *     operationId="updateCar",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of car to update",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id", "category_id", "name", "image", "brand_name", "price_per_day", "stock"},
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="category_id", type="integer", example=2),
 *             @OA\Property(property="name", type="string", example="Toyota Fortuner"),
 *             @OA\Property(property="image", type="string", example="fortuner_updated.jpg"),
 *             @OA\Property(property="brand_name", type="string", example="Toyota Updated"),
 *             @OA\Property(property="price_per_day", type="integer", example=550000),
 *             @OA\Property(property="stock", type="integer", example=4),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Data mobil berhasil diperbarui"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Mobil tidak ditemukan"
 *     )
 * )
 */
class CarSwaggerController extends Controller
{
    public function store(CarRequest $request)
    {
        try {
            $data = $request->validated();

            $car = Car::create([
                'user_id' => $data['user_id'],
                'category_id' => $data['category_id'],
                'name' => $data['name'],
                'image' => $data['image'],
                'brand_name' => $data['brand_name'],
                'price_per_day' => $data['price_per_day'],
                'stock' => $data['stock'],
            ]);

            return response()->json([
                "message" => "Mobil berhasil ditambahkan",
                "data" => new CarResource($car),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menambahkan mobil',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getAllData()
    {
        $data = Car::all();
        return response()->json(["cars" => CarResource::collection($data)], 200);
    }

    public function destroy($id)
    {
        try {
            $car = Car::find($id);

            if (!$car) {
                return response()->json(['error' => 'Mobil tidak ditemukan'], 404);
            }

            $car->delete();

            return response()->json(['message' => 'Mobil berhasil dihapus'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateCarRequest $request, $id)
    {
        try {
            $car = Car::find($id);

            if (!$car) {
                return response()->json([
                    'message' => 'Mobil tidak ditemukan'
                ], 404);
            }

            $car->update($request->validated());

            return response()->json([
                'message' => 'Data mobil berhasil diperbarui',
                'data' => new CarResource($car)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui data mobil',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Resources\CarResource;
use Illuminate\Http\Request;
use App\Models\Car;


/**
 * @OA\Schema(
 *     schema="CarResource",
 *     type="object",
 *     title="Car Resource",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Avanza"),
 *     @OA\Property(property="brand_name", type="string", example="Toyota"),
 *     @OA\Property(property="price", type="number", format="float", example=350000),
 *     @OA\Property(property="type", type="string", example="SUV"),
 *     @OA\Property(property="transmission", type="string", example="automatic"),
 *     @OA\Property(property="fuel", type="string", example="petrol"),
 *     @OA\Property(property="seat", type="integer", example=5),
 *     @OA\Property(property="image", type="string", example="car.jpg"),
 *     @OA\Property(property="available", type="boolean", example=true)
 * )
 */

class CarSwaggerController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/car",
 *     summary="Tambah mobil baru",
 *     tags={"Car"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id","category_id","name","brand_name","price_per_day","stock"},
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="category_id", type="integer", example=2),
 *             @OA\Property(property="name", type="string", example="Avanza"),
 *             @OA\Property(property="image", type="string", example="avanza.jpg"),
 *             @OA\Property(property="brand_name", type="string", example="Toyota"),
 *             @OA\Property(property="price_per_day", type="number", format="float", example=350000),
 *             @OA\Property(property="stock", type="integer", example=10),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Mobil berhasil ditambahkan",
 *         @OA\JsonContent(ref="#/components/schemas/CarResource")
 *     ),
 *     @OA\Response(response=500, description="Terjadi kesalahan saat menambahkan mobil")
 * )
 */
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

    /**
     * @OA\Get(
     *     path="/api/car/category/{categoryId}",
     *     summary="Ambil mobil berdasarkan kategori",
     *     tags={"Car"},
     *     @OA\Parameter(
     *         name="categoryId",
     *         in="path",
     *         required=true,
     *         description="ID kategori",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Daftar mobil berdasarkan kategori",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/CarResource"))
     *     ),
     *     @OA\Response(response=404, description="Tidak ada mobil ditemukan")
     * )
     */
    public function getCarsByCategory($categoryId)
    {
        try {
            $cars = Car::where('category_id', $categoryId)->get();

            if ($cars->isEmpty()) {
                return response()->json([
                    'message' => 'Tidak ada mobil yang ditemukan untuk kategori ini'
                ], 404);
            }

            return response()->json([
                'message' => 'Daftar mobil berdasarkan kategori berhasil diambil',
                'data' => CarResource::collection($cars)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data mobil berdasarkan kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/car",
     *     summary="Ambil semua data mobil",
     *     tags={"Car"},
     *     @OA\Response(
     *         response=200,
     *         description="Semua data mobil",
     *         @OA\JsonContent(
     *             @OA\Property(property="cars", type="array", @OA\Items(ref="#/components/schemas/CarResource"))
     *         )
     *     )
     * )
     */
    public function getAllData()
    {
        $data = Car::all();
        return response()->json(["cars" => CarResource::collection($data)], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/car/{id}",
     *     summary="Hapus mobil",
     *     tags={"Car"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID mobil",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Mobil berhasil dihapus"),
     *     @OA\Response(response=404, description="Mobil tidak ditemukan")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/car/{id}",
     *     summary="Ambil detail mobil",
     *     tags={"Car"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID mobil",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Data mobil berhasil diambil", @OA\JsonContent(ref="#/components/schemas/CarResource")),
     *     @OA\Response(response=404, description="Mobil tidak ditemukan")
     * )
     */
    public function show($id)
    {
        try {
            $car = Car::find($id);

            if (!$car) {
                return response()->json([
                    'message' => 'Mobil tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'message' => 'Data mobil berhasil diambil',
                'data' => new CarResource($car)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data mobil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

 /**
 * @OA\Put(
 *     path="/api/car/{id}",
 *     summary="Update data mobil",
 *     tags={"Car"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID mobil yang akan diupdate",
 *         required=true,
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="category_id", type="integer", example=2),
 *             @OA\Property(property="name", type="string", example="Avanza Updated"),
 *             @OA\Property(property="image", type="string", example="avanza_new.jpg"),
 *             @OA\Property(property="brand_name", type="string", example="Toyota"),
 *             @OA\Property(property="price_per_day", type="number", format="float", example=370000),
 *             @OA\Property(property="stock", type="integer", example=8),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Data mobil berhasil diperbarui",
 *         @OA\JsonContent(ref="#/components/schemas/CarResource")
 *     ),
 *     @OA\Response(response=404, description="Mobil tidak ditemukan"),
 *     @OA\Response(response=500, description="Terjadi kesalahan saat memperbarui data mobil")
 * )
 */
    public function update(CarRequest $request, $id)
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

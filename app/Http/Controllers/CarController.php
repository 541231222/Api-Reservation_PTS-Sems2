<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Resources\CarResource;
use Illuminate\Http\Request;
use App\Models\Car;

class CarController extends Controller
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
                'stock' => $data['stock'], // Tambahkan stock ke data yang disimpan.
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

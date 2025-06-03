<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategorySwaggerController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/category",
     *     summary="Tambah kategori",
     *     tags={"Category"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="SUV")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Kategori berhasil ditambahkan"),
     *     @OA\Response(response=500, description="Terjadi kesalahan saat menambahkan kategori")
     * )
     */
    public function store(CategoryRequest $request)
    {
        try {
            $data = $request->validated();

            $category = Category::create([ 'name' => $data['name'] ]);

            return response()->json([
                "message" => "Kategori berhasil ditambahkan",
                "data" => new CategoryResource($category),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menambahkan kategori',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/category",
     *     summary="Ambil semua data kategori",
     *     tags={"Category"},
     *     @OA\Response(response=200, description="Data kategori berhasil diambil")
     * )
     */
    public function getAllData()
    {
        $data = Category::all();
        return response()->json(["categories" => CategoryResource::collection($data)], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/category/{id}",
     *     summary="Hapus kategori",
     *     tags={"Category"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Kategori berhasil dihapus"),
     *     @OA\Response(response=404, description="Kategori tidak ditemukan"),
     *     @OA\Response(response=500, description="Terjadi kesalahan saat menghapus kategori")
     * )
     */
    public function destroy($id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json(['error' => 'Kategori tidak ditemukan'], 404);
            }

            $category->delete();

            return response()->json(['message' => 'Kategori berhasil dihapus'], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/category/{id}",
     *     summary="Perbarui data kategori",
     *     tags={"Category"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="MPV")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Data kategori berhasil diperbarui"),
     *     @OA\Response(response=404, description="Kategori tidak ditemukan"),
     *     @OA\Response(response=500, description="Terjadi kesalahan saat memperbarui kategori")
     * )
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'message' => 'Kategori tidak ditemukan'
                ], 404);
            }

            $category->update($request->validated());

            return response()->json([
                'message' => 'Data kategori berhasil diperbarui',
                'data' => new CategoryResource($category)
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui data kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

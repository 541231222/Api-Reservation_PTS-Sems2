<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;

/**
 * @OA\Post(
 *     path="/category",
 *     tags={"Category"},
 *     summary="Add a new Category",
 *     description="Store a new category to database",
 *     operationId="storeCategory",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="SUV"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Kategori berhasil ditambahkan"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Terjadi kesalahan saat menambahkan kategori"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/category",
 *     tags={"Category"},
 *     summary="Get all Categories",
 *     description="Retrieve a list of all categories",
 *     operationId="getAllCategories",
 *     @OA\Response(
 *         response=200,
 *         description="List of categories retrieved successfully"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/category/{id}",
 *     tags={"Category"},
 *     summary="Delete a Category",
 *     description="Delete a category by its ID",
 *     operationId="deleteCategory",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of category to delete",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Kategori berhasil dihapus"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Kategori tidak ditemukan"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/category/{id}",
 *     tags={"Category"},
 *     summary="Update a Category",
 *     description="Update category information",
 *     operationId="updateCategory",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of category to update",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="SUV Updated"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Data kategori berhasil diperbarui"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Kategori tidak ditemukan"
 *     )
 * )
 */
class CategorySwaggerController extends Controller
{
    public function store(CategoryRequest $request)
    {
        try {
            $data = $request->validated();

            $category = Category::create([
                'name' => $data['name']
            ]);

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

    public function getAllData()
    {
        $data = Category::all();
        return response()->json(["categories" => CategoryResource::collection($data)], 200);
    }

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

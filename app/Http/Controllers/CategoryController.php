<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
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

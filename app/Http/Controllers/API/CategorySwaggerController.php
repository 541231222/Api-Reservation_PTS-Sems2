<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


/**
 * @OA\Get(
 *     path="/category",
 *     tags={"Category"},
 *     operationId="listCategory",
 *     summary="List of Categories",
 *     description="Retrieve a list of car categories",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             example={
 *                 "success": true,
 *                 "message": "Successfully retrieved categories",
 *                 "data": {
 *                     {"id": 1, "name": "Sedan"},
 *                     {"id": 2, "name": "Truk"}
 *                 }
 *             }
 *         )
 *     )
 * )
 */
class CategorySwaggerController extends Controller
{
    public function listCategory()
    {
        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved categories',
            'data' => [
                ['id' => 1, 'name' => 'Sedan'],
                ['id' => 2, 'name' => 'Truk']
            ]
        ]);
    }
}

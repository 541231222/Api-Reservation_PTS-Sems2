<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 *     path="/user",
 *     tags={"User"},
 *     operationId="listUser",
 *     summary="List of Users",
 *     description="Retrieve a list of users including password",
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             example={
 *                 "success": true,
 *                 "message": "Successfully retrieved users",
 *                 "data": {
 *                     {
 *                         "id": 1,
 *                         "name": "John Doe",
 *                         "email": "john@example.com",
 *                         "password": "password123"
 *                     },
 *                     {
 *                         "id": 2,
 *                         "name": "Jane Smith",
 *                         "email": "jane@example.com",
 *                         "password": "securepassword456"
 *                     }
 *                 }
 *             }
 *         )
 *     )
 * )
 */
class UserSwaggerController extends Controller
{
    public function listUser()
    {
        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved users',
            'data' => [
                [
                    'id' => 1,
                    'name' => 'John Doe',
                    'email' => 'john@example.com',
                    'password' => 'password123',
                ],
                [
                    'id' => 2,
                    'name' => 'Jane Smith',
                    'email' => 'jane@example.com',
                    'password' => 'securepassword456',
                ]
            ]
        ]);
    }
}

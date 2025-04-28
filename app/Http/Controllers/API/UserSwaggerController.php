<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * @OA\Post(
 *     path="/register",
 *     tags={"User"},
 *     summary="Register a new user",
 *     description="Create a new user with provided name, email, and password",
 *     operationId="registerUser",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email", "password"},
 *             @OA\Property(property="name", type="string", example="John Doe"),
 *             @OA\Property(property="email", type="string", example="johndoe@example.com"),
 *             @OA\Property(property="password", type="string", example="secret123"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User successfully created",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/UserResource")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error while creating user"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/user/{id}",
 *     tags={"User"},
 *     summary="Get user data by ID",
 *     description="Retrieve details of a specific user by their ID",
 *     operationId="getUserData",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User data retrieved successfully",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(ref="#/components/schemas/UserResource")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 *
 * @OA\Put(
 *     path="/user/{id}",
 *     tags={"User"},
 *     summary="Update user data",
 *     description="Update name, email, and optionally password of a user",
 *     operationId="updateUser",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "email"},
 *             @OA\Property(property="name", type="string", example="John Doe Updated"),
 *             @OA\Property(property="email", type="string", example="john.doe.updated@example.com"),
 *             @OA\Property(property="password", type="string", example="newpassword123"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User data updated successfully"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 *
 * @OA\Delete(
 *     path="/user/{id}",
 *     tags={"User"},
 *     summary="Delete a user",
 *     description="Delete a user by their ID",
 *     operationId="deleteUser",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User successfully deleted"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */
class UserSwaggerController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            // Validate the request and create a new user
            $data = $request->validated();
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            return response()->json([
                "messages" => "User Berhasil Dibuat",
                "user" => new UserResource($user)
            ], 200);
        } catch (\Exception $e) {
            return response()->json($e, 500);
        }
    }

    public function getData($id)
    {
        $data = User::find($id);
        return response()->json(["users" => new UserResource($data)], 200);
    }

    public function destroy($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'Pengguna tidak ditemukan'], 404);
            }

            $user->delete();

            return response()->json(['message' => 'Pengguna berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'message' => 'Pengguna tidak ditemukan'
                ], 404);
            }

            $data = $request->validated();

            $updateData = [
                'name' => $data['name'],
                'email' => $data['email']
            ];

            if (isset($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);

            return response()->json([
                'message' => 'Data pengguna berhasil diperbarui',
                'user' => new UserResource($user)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat memperbarui data pengguna',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

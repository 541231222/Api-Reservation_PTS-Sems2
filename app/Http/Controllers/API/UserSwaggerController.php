<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserSwaggerController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/user/register",
     *     summary="Registrasi pengguna baru",
     *     tags={"User"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="secret123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="User Berhasil Dibuat"),
     *     @OA\Response(response=500, description="Kesalahan Server")
     * )
     */
    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            return response()->json(["messages" => "User Berhasil Dibuat", "user" => new UserResource($user)], 200);
        } catch (\Exception $e) {
            return response()->json($e, 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/user/{id}",
     *     summary="Ambil data pengguna berdasarkan ID",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Data pengguna ditemukan")
     * )
     */
    public function getData($id)
    {
        $data = User::find($id);
        return response()->json(["users" => new UserResource($data)], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/user/{id}",
     *     summary="Hapus pengguna berdasarkan ID",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Pengguna berhasil dihapus"),
     *     @OA\Response(response=404, description="Pengguna tidak ditemukan"),
     *     @OA\Response(response=500, description="Kesalahan server")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/user",
     *     summary="Ambil semua pengguna",
     *     tags={"User"},
     *     @OA\Response(response=200, description="Daftar semua pengguna berhasil diambil"),
     *     @OA\Response(response=500, description="Kesalahan server")
     * )
     */
    public function getAllUsers()
    {
        try {
            $users = User::all();
            return response()->json([
                'message' => 'Daftar semua pengguna berhasil diambil',
                'users' => UserResource::collection($users)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat mengambil data pengguna',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/user/{id}",
     *     summary="Perbarui data pengguna",
     *     tags={"User"},
     *     security={{"bearerAuth":{}}},
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
     *             @OA\Property(property="email", type="string", example="john_updated@example.com"),
     *             @OA\Property(property="password", type="string", example="newpassword", nullable=true)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Data pengguna berhasil diperbarui"),
     *     @OA\Response(response=404, description="Pengguna tidak ditemukan"),
     *     @OA\Response(response=500, description="Kesalahan server")
     * )
     */
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

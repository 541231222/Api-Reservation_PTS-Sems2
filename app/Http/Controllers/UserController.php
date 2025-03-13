<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class UserController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            //buat variable data yang diisi sama request yang udah di validasi ddi register
            $data = $request->validated();
            //buat variable user dimana menjalaankan perintah untuk membuat user dan di store ke database
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            //return data user dalam bentuk format json dengan messages dan user dengan status 200
            return response()->json(["messages" => "User Berhasil Dibuat", "user" => new UserResource($user)], 200);
            //jika terjadi unexpected error akan di lempar ke catch sehingga program tidak rusak
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

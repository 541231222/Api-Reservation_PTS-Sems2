<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Post(
 *     path="/api/login",
 *     summary="User Login",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *             @OA\Property(property="password", type="string", format="password", example="password123")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful Login",
 *         @OA\JsonContent(
 *             @OA\Property(property="token", type="string", example="1|abc123xyz456token"),
 *             @OA\Property(property="role", type="string", example="admin")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Invalid Credentials",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Credentials are not valid or unauthorized")
 *         )
 *     )
 * )
 */
class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $credentials = $request->validate([
            'email'=> 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($credentials)) {
            return response()->json(['message' => 'Credentials are not valid or unauthorized'], 401);
        }

        $user = auth()->user();

        // Contoh: cek role, hanya izinkan admin dan user
        if (!in_array($user->role, ['admin', 'user'])) {
            return response()->json(['message' => 'Unauthorized role'], 401);
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'role' => $user->role,
        ]);
    }
}

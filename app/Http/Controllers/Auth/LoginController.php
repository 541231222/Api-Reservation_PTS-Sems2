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
 *             @OA\Property(property="token", type="string", example="1|abc123xyz456token")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Invalid Credentials",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Credential Are Not Valid")
 *         )
 *     )
 * )
 */
class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $credential = $request->validate([
            'email'=> 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($credential)) {
            return response()->json(['message' => 'Credential Are Not Valid'], 401);
        }

        $user = auth()->user();

        return response()->json([
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }
}

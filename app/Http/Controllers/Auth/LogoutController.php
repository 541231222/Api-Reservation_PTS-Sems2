<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Post(
 *     path="/api/logout",
 *     summary="Logout user",
 *     tags={"Auth"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Successfully logged out",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Successfully logged out"),
 *             @OA\Property(property="token", type="string", example="Token has been revoked")
 *         )
 *     )
 * )
 */
class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $token = $user->currentAccessToken();

        if ($token) {
            $tokenId = $token->id;
            $token->delete();

            return response()->json([
                'message' => 'Successfully logged out',
                'token' => "Token {$tokenId} has been revoked"
            ]);
        }

        return response()->json([
            'message' => 'No token found',
            'token' => null
        ], 400);
    }
}

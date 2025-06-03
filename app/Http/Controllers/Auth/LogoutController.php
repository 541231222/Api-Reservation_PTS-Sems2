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
 *             @OA\Property(property="message", type="string", example="Successfully Logout"),
 *             @OA\Property(property="token", type="string", example="Token has been revoked")
 *         )
 *     )
 * )
 */
class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        $token = $request->user()->currentAccessToken();
        $tokenId = $token->id;
        $token->delete();

        return response()->json([
            'message' => 'Successfully Logout',
            'token' => "Token {$tokenId} has been revoked"
        ]);
    }
}

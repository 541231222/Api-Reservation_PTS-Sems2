<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Resources\WishlistResource;

/**
 * @OA\Schema(
 *     schema="Wishlist",
 *     type="object",
 *     required={"user_id", "car_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="car_id", type="integer", example=2),
 *     @OA\Property(property="created_at", type="string", format="date-time")
 * )
 *
 * @OA\Tag(
 *     name="Wishlist",
 *     description="Wishlist API Endpoints"
 * )
 */
class WishlistSwaggerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/wishlists",
     *     tags={"Wishlist"},
     *     summary="List all wishlists",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Wishlist"))
     *     )
     * )
     */

    public function index()
    {
        return WishlistResource::collection(Wishlist::with('car')->get());
    }

    /**
     * @OA\Post(
     *     path="/api/wishlists",
     *     tags={"Wishlist"},
     *     security={{"bearerAuth":{}}},
     *     summary="Add car to wishlist",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id","car_id"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="car_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Wishlist created",
     *         @OA\JsonContent(ref="#/components/schemas/Wishlist")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'car_id'  => 'required|exists:cars,id',
        ]);

        $wishlist = Wishlist::firstOrCreate($data);

        return new WishlistResource($wishlist);
    }

    /**
     * @OA\Get(
     *     path="/api/wishlists/{id}",
     *     tags={"Wishlist"},
     *     summary="Get wishlist item by ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent(ref="#/components/schemas/Wishlist")),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function show($id)
    {
        return new WishlistResource(Wishlist::with('car')->findOrFail($id));
    }

    /**
     * @OA\Put(
     *     path="/api/wishlists/{id}",
     *     tags={"Wishlist"},
     *     security={{"bearerAuth":{}}},
     *     summary="Update wishlist item",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id","car_id"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="car_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent(ref="#/components/schemas/Wishlist")),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'car_id'  => 'required|exists:cars,id',
        ]);

        $wishlist = Wishlist::findOrFail($id);
        $wishlist->update($data);

        return new WishlistResource($wishlist);
    }

    /**
     * @OA\Delete(
     *     path="/api/wishlists/{id}",
     *     tags={"Wishlist"},
     *     security={{"bearerAuth":{}}},
     *     summary="Delete wishlist item",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Deleted", @OA\JsonContent(@OA\Property(property="message", type="string", example="Deleted"))),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function destroy($id)
    {
        $wishlist = Wishlist::findOrFail($id);
        $wishlist->delete();

        return response()->json(['message' => 'Deleted']);
    }
}

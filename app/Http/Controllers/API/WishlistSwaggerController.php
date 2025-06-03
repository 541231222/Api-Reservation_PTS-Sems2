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
     * @OA\Delete(
     *     path="/api/wishlists/{id}",
     *     tags={"Wishlist"},
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

        /**
     * @OA\Get(
     *     path="/api/wishlists/user/{user_id}",
     *     tags={"Wishlist"},
     *     summary="Get wishlist by user ID",
     *     @OA\Parameter(name="user_id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Wishlist"))),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function getByUserId($user_id)
    {
        $wishlist = Wishlist::with('car')->where('user_id', $user_id)->get();

        if ($wishlist->isEmpty()) {
            return response()->json(['message' => 'Wishlist not found'], 404);
        }

        return WishlistResource::collection($wishlist);
    }

    /**
     * @OA\Get(
     *     path="/api/wishlists/check",
     *     tags={"Wishlist"},
     *     summary="Check if a car is in the user's wishlist",
     *     @OA\Parameter(name="user_id", in="query", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="car_id", in="query", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Result", @OA\JsonContent(
     *         @OA\Property(property="exists", type="boolean", example=true)
     *     ))
     * )
     */
    public function check(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'car_id'  => 'required|exists:cars,id',
        ]);

        $exists = Wishlist::where($data)->exists();

        return response()->json(['exists' => $exists]);
    }


    /**
     * @OA\Get(
     *     path="/api/wishlists/paginate",
     *     tags={"Wishlist"},
     *     summary="Paginated wishlists with optional sorting",
     *     @OA\Parameter(name="page", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="sort", in="query", description="Sort by created_at (asc|desc)", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent(
     *         @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Wishlist")),
     *         @OA\Property(property="meta", type="object")
     *     ))
     * )
     */
    public function paginate(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $sort = $request->get('sort', 'desc');

        $wishlists = Wishlist::with('car')
            ->orderBy('created_at', $sort)
            ->paginate($perPage);

        return WishlistResource::collection($wishlists);
    }


    /**
     * @OA\Get(
     *     path="/api/wishlists/count/{user_id}",
     *     tags={"Wishlist"},
     *     summary="Count wishlist items per user",
     *     @OA\Parameter(name="user_id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent(@OA\Property(property="count", type="integer")))
     * )
     */
    public function countByUser($user_id)
    {
        $count = Wishlist::where('user_id', $user_id)->count();

        return response()->json(['count' => $count]);
    }


    /**
     * @OA\Delete(
     *     path="/api/wishlists/clear/{user_id}",
     *     tags={"Wishlist"},
     *     summary="Clear all wishlist items for a user",
     *     @OA\Parameter(name="user_id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Success", @OA\JsonContent(@OA\Property(property="message", type="string", example="Wishlist cleared")))
     * )
     */
    public function clearByUser($user_id)
    {
        Wishlist::where('user_id', $user_id)->delete();

        return response()->json(['message' => 'Wishlist cleared']);
    }

}

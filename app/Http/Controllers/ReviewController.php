<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Resources\ReviewResource;

/**
 * @OA\Tag(
 *     name="Reviews",
 *     description="Endpoints for managing car reviews"
 * )
 */

class ReviewController extends Controller
{
    public function index()
    {
        return ReviewResource::collection(Review::all());
    }

    public function store(Request $request)
    {

/**
 * @OA\Post(
 *     path="/api/reviews",
 *     tags={"Reviews"},
 *     summary="Create a new review",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id","car_id","rating"},
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="car_id", type="integer", example=2),
 *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=4),
 *             @OA\Property(property="comment", type="string", example="Mobil sangat nyaman."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Review")
 *     )
 * )
 */

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'car_id'  => 'required|exists:cars,id',
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = Review::create($data);

        return new ReviewResource($review);
    }

    public function show($id)
    {
        return new ReviewResource(Review::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);

        $data = $request->validate([
            'rating'  => 'sometimes|required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review->update($data);

        return new ReviewResource($review);
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json(['message' => 'Review deleted']);
    }
}

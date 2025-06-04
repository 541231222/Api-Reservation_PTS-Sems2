<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Resources\ReviewResource;

/**
 * @OA\Tag(
 *     name="Reviews",
 *     description="Endpoints for managing car reviews"
 * )
 */

/**
 * @OA\Schema(
 *     schema="Review",
 *     type="object",
 *     title="Review",
 *     required={"user_id", "car_id", "rating"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="car_id", type="integer", example=2),
 *     @OA\Property(property="rating", type="integer", example=5),
 *     @OA\Property(property="comment", type="string", example="Great experience"),
 *     @OA\Property(property="created_at", type="string", format="date-time")
 * )
 */

class ReviewSwaggerController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/reviews/all",
     *     tags={"Reviews"},
     *     summary="Get list of all reviews",
     *     @OA\Response(
     *         response=200,
     *         description="List of reviews",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Review"))
     *     )
     * )
     */
    public function index()
    {
        return ReviewResource::collection(Review::all());
    }

    /**
     * @OA\Post(
     *     path="/api/reviews",
     *     tags={"Reviews"},
     *     security={{"bearerAuth":{}}},
     *     summary="Create a new review",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id","car_id","rating"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="car_id", type="integer", example=2),
     *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=4),
     *             @OA\Property(property="comment", type="string", example="Mobil sangat nyaman.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Review")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'car_id'  => 'required|exists:cars,id',
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = Review::create($data);

        return new ReviewResource($review);
    }

    /**
     * @OA\Get(
     *     path="/api/reviews/{id}",
     *     tags={"Reviews"},
     *     summary="Get review by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review found",
     *         @OA\JsonContent(ref="#/components/schemas/Review")
     *     ),
     *     @OA\Response(response=404, description="Review not found")
     * )
     */
    public function show($id)
    {
        return new ReviewResource(Review::findOrFail($id));
    }

    /**
     * @OA\Put(
     *     path="/api/reviews/update/{id}",
     *     tags={"Reviews"},
     *     security={{"bearerAuth":{}}},
     *     summary="Update an existing review",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="rating", type="integer", example=4),
     *             @OA\Property(property="comment", type="string", example="Sudah diperbarui")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review updated",
     *         @OA\JsonContent(ref="#/components/schemas/Review")
     *     )
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/reviews/delete/{id}",
     *     tags={"Reviews"},
     *     security={{"bearerAuth":{}}},
     *     summary="Delete a review by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Review deleted")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Review not found")
     * )
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json(['message' => 'Review deleted']);
    }
}

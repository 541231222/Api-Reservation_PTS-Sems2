<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Resources\WishlistResource;

class WishlistController extends Controller
{
    public function index()
    {
        return WishlistResource::collection(Wishlist::with('car')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'car_id'  => 'required|exists:cars,id',
        ]);

        $wishlist = Wishlist::firstOrCreate($data);

        return new WishlistResource($wishlist);
    }

    public function show($id)
    {
        return new WishlistResource(Wishlist::with('car')->findOrFail($id));
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::findOrFail($id);
        $wishlist->delete();

        return response()->json(['message' => 'Deleted']);
    }
}

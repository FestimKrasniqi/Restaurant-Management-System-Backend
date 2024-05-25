<?php


namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Post(
 *     path="/api/create-Review",
 *     summary="Insert a new review",
 *     tags={"Review"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Review data",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(property="comment", type="string"),
 *                 @OA\Property(property="rating", type="number"),
 *                
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Review created successfully"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="object", example="Validation error"),
 *             @OA\Property(property="status", type="boolean", example=false),
 *         )
 *     ),
 * )
 * 
 * * @OA\Get(
 *     path="/api/getReviews",
 *     summary="Get all reviews",
 *     tags={"Review"},
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="comment", type="string"),
 *                 @OA\Property(property="rating", type="number"),
 *                 @OA\Property(property="user", ref="#/components/schemas/User"),
 *                
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized"),
 *         )
 *     ),
 * )
 * 
 * 
 */

class ReviewController {

function insertReview(Request $req) {

   $validate = Validator::make($req->all(),[
   'comment' => 'required|string',
   'rating' => 'required|numeric'
   ]);

   if($validate->fails()) {
    return response()->json(['message' => $validate->errors()->fails(),'status' => false],422);
   }

   if(!Gate::allows('manage-review')) {
    return response()->json(['message' => 'Unauthorized'],401);
   }


   $user = Auth::user();

   $review = Review::create([
    'comment' => $req->comment,
    'rating' => $req->rating,
   ]);


   $review->user()->associate($user);
   $review->save();

   

   return response()->json(['message' => 'Review created successfully'],200);

    }


    public function getAllReviews() {
        $review = Review::with('user')->get();

        return response()->json($review,200);
    }
}


?>
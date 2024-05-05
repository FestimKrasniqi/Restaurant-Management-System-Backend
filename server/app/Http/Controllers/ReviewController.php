<?php


namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

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
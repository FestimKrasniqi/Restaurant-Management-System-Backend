<?php


namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class BookingController {

    public function insertBooking (Request $req) {

        $validate = Validator::make($req->all(),[
            'dateTime' => [
                'required',
                'date',
                'date_format:Y-m-d H:i:s',
                function ($attribute, $value, $fail) {
                    if (strtotime($value) < strtotime('now')) {
                        $fail('The date must be in the future.');
                    }
                }
            ],
            'number_of_guests' => 'required|numeric'
        ]);

        if($validate->fails()){
            return response()->json(['message'=>$validate->errors()->first(),'status'=>false],422);
        };

        if(!Gate::allows('create-booking')) {
            return response()->json(['message'=>'Unauthorized'],404);
        }

        $user = Auth::user();

        $user->phoneNumber = $req->input('phoneNumber');
        $user->save();

        $booking = Booking::create([
            'dateTime' => $req->dateTime,
            'number_of_guests' => $req->number_of_guests,
            'phone_number' => $req->input('phoneNumber'),
        ]);

        $booking->user()->associate($user);

        $booking->save();

        return response()->json(['message'=>'Booking created successfully'],200);
        
    }

    public function getAllBookings() {

        $booking = Booking::with('user')->get();

        return response()->json($booking,200);
    }

    public function getBookingById($id) {

        $booking = Booking::find($id);

        if(!$booking) {
            return response()->json(['message'=>'Not found'],404);
        }

        return response()->json($booking,200);
    }

    public function destroy($id) {

        $booking = Booking::find($id);

        if(!$booking) {
            return response()->json(['message'=>'not found'],404);
        }

        if(!Gate::allows('manage-booking',$booking)) {
            return response()->json(['message'=>'Unauthorized'],403);
        }


        $booking->delete();

        return response()->json(['message'=>'Booking deleted with success'],200);
    }

    public function index() {

        $user = Auth::user();

        $booking = Booking::where('user_id',$user->id)->with('user')->get();
        return response()->json($booking,200);
    }

    public function updateBooking(Request $req,$id) {
        
        $validate = Validator::make($req->all(),[
            'dateTime' => [
                'sometimes',
                'date',
                'date_format:Y-m-d H:i:s',
                function ($attribute, $value, $fail) {
                    if (strtotime($value) < strtotime('now')) {
                        $fail('The date must be in the future.');
                    }
                }
            ],
            'number_of_guests' => 'required|sometimes'
        ]);

        if($validate->fails()) {
            return response()->json([
                'message' => $validate->errors()->first(),
                'status' =>false
            ],422);
        }

        $booking = Booking::find($id);

        if(!Gate::allows('manage-booking',$booking)) {
            return response()->json(['message'=>'Unauthorized'],403);
        }

        $booking->update([
            'dateTime' => $req->dateTime ?? $booking->dateTime,
            'number_of_guests' => $req->number_of_guests ?? $booking->number_of_guests
        ]);

        if($req->has('phoneNumber')) {
            $booking->user()->update(['phoneNumber'=> $req->phoneNumber ?? $booking->user->phoneNumber]);
        }

        return response()->json(['message'=>'Booking Updated with success'],200);
    }

}


?>
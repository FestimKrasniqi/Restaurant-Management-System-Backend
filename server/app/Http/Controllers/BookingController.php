<?php


namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

/** @OA\Post(
    *     path="/api/create-booking",
    *     summary="Insert a new booking",
    *     tags={"Booking"},
    *     @OA\RequestBody(
    *         required=true,
    *         description="Booking data",
    *         @OA\MediaType(
    *             mediaType="multipart/form-data",
    *             @OA\Schema(
    *                 @OA\Property(property="dateTime", type="string", format="date-time", example="2024-05-15 18:00:00"),
    *                 @OA\Property(property="number_of_guests", type="integer", example=4),
    *                 @OA\Property(property="phoneNumber", type="string", example="123456789"),
    *             )
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Booking created successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="Booking created successfully"),
    *         )
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="Unauthenticated"),
    *         )
    *     ),
    *     @OA\Response(
    *         response=422,
    *         description="Validation error",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="The date must be in the future"),
    *             @OA\Property(property="status", type="boolean", example=false),
    *         )
    *     ),
    * )

    *    @OA\Get(
    *     path="/api/getAllBookings",
    *     summary="Get all bookings",
    *     tags={"Booking"},
   
    *     @OA\Response(
    *         response=200,
    *         description="Successful operation",
    *         @OA\JsonContent(
    *             type="array",
    *             @OA\Items(ref="#/components/schemas/booking"),
    *         )
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="Unauthenticated"),
    *         )
    *     ),
    * )

    *     @OA\Delete(
    *     path="/api/deleteBooking/{id}",
    *     summary="Delete a booking",
    *     tags={"Booking"},
   
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         description="ID of the booking to delete",
    *         @OA\Schema(
    *             type="integer",
    *             format="int64",
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Booking deleted successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="Booking deleted with success"),
    *         )
    *     ),
    *     @OA\Response(
    *         response=403,
    *         description="Forbidden",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="Unauthorized"),
    *         )
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Not found",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="Booking not found"),
    *         )
    *     ),
    * )

    *    @OA\Patch(
    *      path="/api/updateBooking/{id}",
    *      summary="Update a booking",
    *      tags={"Booking"},
    
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         description="ID of the booking to update",
    *        @OA\Schema(
    *             type="integer",
    *             format="int64",
    *         )
    *     ),
    *     @OA\RequestBody(
    *         required=true,
    *         description="Booking data to update",
    *         @OA\JsonContent(
    *             @OA\Property(property="dateTime", type="string", format="date-time", example="2024-05-20 14:00:00"),
    *             @OA\Property(property="number_of_guests", type="integer", example=4),
    *             @OA\Property(property="phoneNumber", type="string", example="1234567890"),
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Booking updated successfully",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="Booking Updated with success"),
    *         )
    *     ),
    *     @OA\Response(
    *         response=403,
    *         description="Forbidden",
    *        @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="Unauthorized"),
    *         )
    *     ),
    *     @OA\Response(
    *         response=422,
    *         description="Validation error",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="The date must be in the future."),
    *         )
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Not found",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="Booking not found"),
    *         )
    *     ),
    * )
    *   @OA\Get(
    *     path="/api/index",
    *     summary="Get all bookings for the authenticated user",
    *     tags={"Booking"},
    *    
    *     @OA\Response(
    *         response=200,
    *         description="Successful operation",
    *         @OA\JsonContent(
    *             type="array",
    *             @OA\Items(ref="#/components/schemas/booking"),
    *         )
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="Unauthenticated")
    *         )
    *     ),
    * )

    *    @OA\Get(
    *     path="/api/getBookingById/{id}",
    *     summary="Get a booking by ID",
    *     tags={"Booking"},
 
    *     @OA\Parameter(
    *         name="id",
    *         in="path",
    *         required=true,
    *         description="ID of the booking",
    *         @OA\Schema(
    *             type="integer",
    *             format="int64",
    *         )
    *     ),
    *     @OA\Response(
    *         response=200,
    *         description="Successful operation",
    *         @OA\JsonContent(ref="#/components/schemas/booking"),
    *     ),
    *     @OA\Response(
    *         response=401,
    *         description="Unauthorized",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="Unauthenticated"),
    *         )
    *     ),
    *     @OA\Response(
    *         response=404,
    *         description="Booking not found",
    *         @OA\JsonContent(
    *             @OA\Property(property="message", type="string", example="Not found"),
    *         )
    *     ),
    * )

*/



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

        $booking = Booking::with('user')->find($id);

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
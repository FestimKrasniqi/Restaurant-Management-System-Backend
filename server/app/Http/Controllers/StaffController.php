<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

/**
 * @OA\Post(
 *     path="/api/add-staff",
 *     summary="Insert a new staff member",
 *     tags={"Staff"},
 *  
 *     @OA\RequestBody(
 *         required=true,
 *         description="Staff member data",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(property="FullName", type="string", example="John Doe"),
 *                 @OA\Property(property="role", type="string", example="Manager"),
 *                 @OA\Property(property="salary", type="number", format="float", example=2500.00),
 *                 @OA\Property(property="end_time", type="string", format="time", example="17:00"),
 *                 @OA\Property(property="start_time", type="string", format="time", example="09:00"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Staff member added successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Staff added successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Unauthorized")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Validation error"),
 *             @OA\Property(property="status", type="boolean", example=false)
 *         )
 *     ),
 * )
 * 
 *   @OA\Get(
 *     path="/api/allStaff",
 *     summary="Get all staff members",
 *     tags={"Staff"},
 *  
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/Staff"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Unauthorized"),
 *         )
 *     ),
 * )
 * 
 *  @OA\Patch(
 *     path="/api/updateStaff/{id}",
 *     summary="Update staff member",
 *     tags={"Staff"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the staff member to update",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Staff member data",
 *         @OA\JsonContent(
 *             @OA\Property(property="FullName", type="string", example="John Doe"),
 *             @OA\Property(property="salary", type="number", format="float", example=2000.00),
 *             @OA\Property(property="role", type="string", example="Manager"),
 *             @OA\Property(property="start_time", type="string", format="time", example="09:00"),
 *             @OA\Property(property="end_time", type="string", format="time", example="17:00"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Staff updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Staff updated with success"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Unauthorized"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Staff not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Staff not found"),
 *             @OA\Property(property="message", type="string", example="The staff member with the specified ID was not found"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Validation error"),
 *             @OA\Property(property="status", type="boolean", example=false),
 *         )
 *     ),
 * )
 *
 * @OA\Delete(
 *     path="/api/deleteStaff/{id}",
 *     summary="Delete staff member",
 *     tags={"Staff"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the staff member to delete",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Staff deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Staff deleted with success"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Unauthorized"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Staff not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Staff not found"),
 *             @OA\Property(property="message", type="string", example="The staff member with the specified ID was not found"),
 *         )
 *     ),
 * )
 * * @OA\Get(
 *     path="/api/staff/{id}",
 *     summary="Get staff member by ID",
 *     tags={"Staff"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the staff member",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/staff"),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Staff member not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="This staff member doesnt exist"),
 *         )
 *     ),
 * )
 * 
 */


class StaffController {

    function insertStaff(Request $req) {
        $validate = Validator::make($req->all(),[
            'FullName' => 'required|string|max:255',
            'role' => 'required|string',
            'salary' => 'required|numeric',
            'end_time' => 'required|date_format:H:i',
            'start_time' => 'required|date_format:H:i'
        ]);

        

        if($validate->fails()) {
            return response()->json([
                "message"=> $validate->errors()->first(),
                "status" => false
            ],422);
         }

        
         if(!Gate::allows('manage-staff')) {
            return response()->json(["error"=> 'Unauthorized'],401);
         }

         $shift = Shift::firstorCreate(['start_time'=>$req->start_time,'end_time'=>$req->end_time]);

         $staff = Staff::create([
            'FullName' => $req->FullName,
            'salary' => $req->salary,
            'role' => $req->role
         ]);

         $staff->shift()->associate($shift);

         

         $staff->save();

         return response()->json([
         'message' => 'Staff added successfully'
         ],200);


    }

    public function getStaffById(Request $req,$id) {
        $staff = Staff::with('shift')->find($id);

        if(!$staff) {
            return response()->json(['message'=>'This staff member doesnt exist']);
        }

        return response()->json($staff,200);
    }

    public function getAllStaff(Request $req) {
        $staff =  Staff::with('shift')->get();

        return response()->json($staff,200);
    }

    public function updateStaff(Request $req,$id) {
        $validator = Validator::make($req->all(), [
            'FullName' => 'sometimes|string|max:255',
            'salary' => 'sometimes|numeric',
            'role' => 'sometimes|string',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i'

        ]);

        if($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first(),'status' => false],422);
        }

        $staff = Staff::findorFail($id);
        

        if (!Gate::allows('manage-staff')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $staff->update([
            'FullName' => $req->FullName ?? $staff->FullName,
            'salary' => $req->salary ?? $staff->salary,
            'role' => $req->role ?? $staff->role

        ]);

        if($req->has('start_time')) {
         $staff->shift()->update(['start_time' => $req->start_time ?? $staff->shift->start_time]);

        }

        if($req->has('end_time')) {
            $staff->shift()->update(['end_time' => $req->end_time ?? $staff->shift->end_time]);
        }

        return response()->json(['message' => 'Staff updated with success'],200);
        
    }

    public function destroy(Request $req,$id) {
        $staff = Staff::findOrFail($id);

        if (!Gate::allows('manage-staff')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $staff->delete();

        return response()->json(['message' => 'Staff deleted with success'],200);
    }
}

?>
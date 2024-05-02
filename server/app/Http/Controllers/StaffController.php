<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;


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
            'start_time' => 'sometimes|date_format:H:i:s',
            'end_time' => 'sometimes|date_format:H:i:s'

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
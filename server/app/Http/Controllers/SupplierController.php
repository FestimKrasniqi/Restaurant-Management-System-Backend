<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use App\Models\Supplier;
use Illuminate\Http\Request;


class SupplierController  {

function insertSupplier(Request $req) {
    $validate = Validator::make($req->all(),[
        'name' => 'required|string',
        'phoneNumber' => 'required|string',
        'city' => 'required|string'
    ]);

    if($validate->fails()) {
        return response()->json(['message' => $validate->errors()->first()],422);
    }

    if(!Gate::allows('manage-supplier')) {
        return response()->json(['message' => 'Unauthorized'],401);
    }

    $supplier = Supplier::create([
        'name' => $req->name,
        'phoneNumber' => $req->phoneNumber,
        'city' => $req->city
    ]);

    $supplier->save();

    return response()->json(['message' => 'Supplier created successfully'],200);
}


function getSupplier() {

    $supplier = Supplier::all();

    return response()->json($supplier,200);

}

function destroy($id) {
    $supplier = Supplier::find($id);

    if(!$supplier) {
        return response()->json(['message'=>'Supplier doesnt exist'],404);
    }

    $supplier->delete();

    return response()->json(['message' => 'Supplier deleted successfully'],200);
}

function getSupplierById($id) {
    $supplier = Supplier::find($id);

    if(!Gate::allows('manage-supplier')) {
        return response()->json(['message' => 'Unauthorized'],401);
    }

    if(!$supplier) {
        return response()->json(['message'=>'Supplier doesnt exist'],404);
    }

    return response()->json($supplier,200);
}

function updateSupplier(Request $req,$id) {

    $validate=Validator::make($req->all(),[
        'name' => 'sometimes|string',
        'phoneNumber' => 'sometimes|string',
        'city' => 'sometimes|string'
    ]);

    if($validate->fails()) {
        return response()->json(['message' => $validate->errors()->first()],422);
    }

    if(!Gate::allows('manage-supplier')) {
        return response()->json(['message' => 'Unauthorized'],401);
    }

   
    $supplier = Supplier::find($id);

    if(!$supplier) {
        return response()->json(['message'=>'Supplier doesnt exist'],404);
    }


    $supplier->update([
        'name'=> $req->name ?? $supplier->name,
        'phoneNumber'=> $req->phoneNumber ?? $supplier->phoneNumber,
        'city' => $req->city ?? $supplier->city

    ]);

return response()->json(['message'=>'Table updated with success'],200);

}

}




?>
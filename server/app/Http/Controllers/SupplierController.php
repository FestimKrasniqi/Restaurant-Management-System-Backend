<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use App\Models\Supplier;
use Illuminate\Http\Request;

/**
 * @OA\Post(
 *     path="/api/insertSupplier",
 *     summary="Insert a new supplier",
 *     tags={"Supplier"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Supplier data",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(property="name", type="string", example="Supplier Name"),
 *                 @OA\Property(property="phoneNumber", type="string", example="+38344123456"),
 *                 @OA\Property(property="city", type="string", example="Supplier City")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Supplier created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Supplier created successfully"),
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
 *             @OA\Property(property="message", type="string", example="Validation error")
 *         )
 *     ),
 * )
 * 
 * * @OA\Get(
 *     path="/api/getSupplier",
 *     summary="Get all suppliers",
 *     tags={"Supplier"},
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(),
 *         )
 *     ),
 * )
 * 
 *  @OA\Patch(
 *     path="/api/updateSupplier/{id}",
 *     summary="Update a supplier",
 *     tags={"Supplier"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the supplier to update",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Supplier data",
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Updated Supplier Name"),
 *             @OA\Property(property="phoneNumber", type="string", example="123456789"),
 *             @OA\Property(property="city", type="string", example="Updated City"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Supplier updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Supplier updated with success"),
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
 *         response=404,
 *         description="Supplier not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Supplier doesnt exist"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Validation error"),
 *         )
 *     ),
 * )
 * 
 *  @OA\Delete(
 *     path="/api/deleteSupplier/{id}",
 *     summary="Delete a supplier",
 *     tags={"Supplier"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the supplier to delete",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Supplier deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Supplier deleted successfully"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Supplier not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Supplier doesnt exist"),
 *         )
 *     ),
 * )
 * 
 * * @OA\Get(
 *     path="/api/getSupplierById/{id}",
 *     summary="Get supplier by ID",
 *     tags={"Supplier"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the supplier",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/supplier"),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Supplier not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Supplier doesn't exist"),
 *         )
 *     ),
 * )
 * 
 */


class SupplierController  {

function insertSupplier(Request $req) {
    $validate = Validator::make($req->all(),[
        'name' => 'required|string|unique:suppliers',
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
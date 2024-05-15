<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

/**
 * @OA\Post(
 *     path="/api/insertTable",
 *     summary="Insert a new table",
 *     tags={"Table"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Table data",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(property="table_name", type="string", example="Table 1"),
 *                 @OA\Property(property="capacity", type="integer", example=4),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Table created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Table created successfully"),
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
 *             @OA\Property(property="message", type="string", example="Validation error"),
 *             @OA\Property(property="status", type="boolean", example=false),
 *         )
 *     ),
 * )
 * 
 * * @OA\Get(
 *     path="/api/getAllTables",
 *     summary="Get all tables",
 *     tags={"Table"},
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(),
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized")
 *         )
 *     ),
 * )
 * 
 *  @OA\Patch(
 *     path="/api/updateTable/{id}",
 *     summary="Update a table",
 *     tags={"Table"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the table to update",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Table data",
 *         @OA\JsonContent(
 *             @OA\Property(property="table_name", type="string", example="Table 1"),
 *             @OA\Property(property="capacity", type="integer", example=4),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Table updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Table updated successfully")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized")
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
 * * @OA\Delete(
 *     path="/api/deleteTable/{id}",
 *     summary="Delete a table",
 *     tags={"Table"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the table to delete",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Table deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Table Deleted successfully"),
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
 * * @OA\Get(
 *     path="/api/getTableById/{id}",
 *     summary="Get table by ID",
 *     tags={"Table"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the table",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/table"),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Table not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Table doesn't exist"),
 *         )
 *     ),
 * )
 * 
 * 
 */


class TableController {

function insert(Request $req) {

$validate = Validator::make($req->all(),[
    'table_name' => 'required|string|max:255',
    'capacity' => 'required|numeric'
]);

if($validate->fails()) {
    return response()->json(['message' => $validate->errors()->first(),
    'status' => false],422);
}

if(!Gate::allows('manage-tables')) {
    return response()->json(['message' => 'Unauthorized'],401);
}

$table = Table::create([
    'table_name' => $req->table_name,
    'capacity' => $req->capacity
]);

$table->save();

return response()->json(['message' => 'Table created successfully'],200);

}

function getAllTables(Request $req) {

    $table = Table::get();

    return response()->json($table,200);
}

function getTableById(Request $req,$id) {
    $table = Table::find($id);

    if(!$table) {
        return response()->json(['message' => 'Table doesnt exist']);
    }

    return response()->json($table,200);
}

function updateTable(Request $req,$id) {

$validate = Validator::make($req->all(),[
    'table_name' => 'sometimes|string|max:255',
    'capacity' => 'sometimes|numeric'
]);

if($validate->fails()) {
    return response()->json(['message' => $validate->errors()->fails(),'status' => false],422);
}

if(!Gate::allows('manage-tables')) {
    return response()->json(['message' => 'Unauthorized'],401);
}

$table = Table::findOrFail($id);

$table->update([
    'table_name' => $req->table_name ?? $table->table_name,
    'capacity' => $req->capacity ?? $table->capacity
]);

return response()->json(['message' => 'Table updated successfully'],200);

}

public function destroy(Request $req,$id) {

    $table = Table::findOrFail($id);

    if(!$table) {
        return response()->json(['message' => 'Unauthorized'],401);
    }

    $table->delete();

    return response()->json(['message ' => 'Table Deleted successfully'],200);
}

}


?>
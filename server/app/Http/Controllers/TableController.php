<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

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
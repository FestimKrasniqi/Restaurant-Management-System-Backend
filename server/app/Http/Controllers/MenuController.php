<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;


class MenuController {

    function  insertMenu (Request $req) {

    
        $validate = Validator::make($req->all(), [
         'name' => 'required|string|max:255',
         'description' => 'required|string',
         'price' => 'required|numeric',
         'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
         'category_name' => 'required|string'
         

        ]);

        if($validate->fails()) {
            return response()->json([
                'message'=> $validate->errors()->first(),
                'status'=> false
            ],422);
        }

        if (!Gate::allows('manage-menu')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
       

        $category = Category::firstOrCreate(['category_name'=> $req->category_name]);

        $menu = Menu::create([
            'name'=> $req->name,
            'description'=> $req->description,
            'price' => $req->price,
            'image_url' => $req->file('image_url')->store('products')
        ]);

        $menu->category()->associate($category);
       

        $menu->save();

        return response()->json([
            'message' => 'Menu item created successfully'
        ],200);

        
    }


    function getMenuByCategory(Request $req,$categoryName) {

     $category = Category::where('category_name',$categoryName)->first();

     if(!$category) {
        return response()->json(['error' => 'Category not found'],404);
     }

     $menus = $category->menus;

     return response()->json($menus,200);
    }


    function getMenuById(Request $req,$id) {

     $menu = Menu::with('category')->find($id);

     if(!$menu) {
        return response()->json(['error' => 'Menu not found'],404);
     }
     

     return response()->json($menu,200);
    }



    function allMenu(Request $req) {
        $menus = Menu::with('category')->get();

        return response()->json($menus,200);
    }


    function updateMenu (Request $req, $id) {

        $validator = Validator::make($req->all(), [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            //'image_url' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_name' => 'sometimes|string'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'status' => false
            ], 422);
        }
    
        
        $menu = Menu::findOrFail($id);

        if (!Gate::allows('manage-menu')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
    
       
        $menu->update([
            'name' => $req->name ?? $menu->name,
            'price' => $req->price ?? $menu->price,
            'description' => $req->description ?? $menu->description,
            'image_url' => $req->file('image_url') ? $req->file('image_url')->store('products') : $menu->image_url
        ]);
    
    
        if ($req->has('category_name')) {
            $menu->category()->update(['category_name' => $req->category_name]);
        }
    
        return response()->json(["message" => "Menu updated successfully","menu"=>$menu], 200);
    }


    public function destroy(Request $req, $id) {
        $menu = Menu::findOrFail($id);

        if (!Gate::allows('manage-menu')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $menu->delete();

        return response()->json(["message"=>"Menu deleted successfully"],200);

    }

}

?>
<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        $category = Category::firstOrCreate(['category_name'=> $req->category_name]);

        $menu = Menu::create([
            'name'=> $req->name,
            'description'=> $req->description,
            'price' => $req->price,
            'image_url' => $req->file('image_url')->store('products')
        ]);


        
        $menu->category()->associate($category);
       

        $menu->save();

        $menusInCategory = $category->menus;

        return response()->json([
            'message' => 'Menu item created successfully',
            'menus_in_category' => $menusInCategory
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

    function allMenu(Request $req) {
        $menus = Menu::with('category')->get();

        return response()->json($menus,200);
    }
}

?>
<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Post(
 *     path="/api/create-menu",
 *     summary="Insert a new menu item",
 *     tags={"Menu"},
 *  
 *     @OA\RequestBody(
 *         required=true,
 *         description="Menu item data",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(property="name", type="string", example="Burger"),
 *                 @OA\Property(property="description", type="string", example="Delicious burger"),
 *                 @OA\Property(property="price", type="number", format="float", example=10.99),
 *                @OA\Property(property="image_url", type="string", format="binary", example="base64-encoded-image"),
 *                 @OA\Property(property="category_name", type="string", example="Main Course"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Menu item created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Menu item created successfully")
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
 *         ),
 *     ),
 * )
 * 
 * @OA\Get(
 *     path="/api/allMenus",
 *     summary="Get all menu items",
 *     tags={"Menu"},
 *  
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/menu")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized")
 *         ),
 *     ),
 *   )
 *  @OA\Patch(
 *     path="/api/updateMenu/{id}",
 *     summary="Update a menu item",
 *     tags={"Menu"},
 *    
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the menu item to update",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Menu item data",
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Updated Burger"),
 *             @OA\Property(property="description", type="string", example="Updated description"),
 *             @OA\Property(property="price", type="number", format="float", example=15.99),
 *            
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Menu updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Menu updated successfully"),
 *             @OA\Property(property="menu", ref="#/components/schemas/menu"),
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
 *         description="Menu not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Menu not found"),
 *             @OA\Property(property="message", type="string", example="The menu item with the specified ID was not found"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Validation error"),
 *             @OA\Property(property="errors", type="object"),
 *         )
 *     ),
 * )
 * 
 *  @OA\Delete(
 *     path="/api/delete-menu/{id}",
 *     summary="Delete a menu item",
 *     tags={"Menu"},
 *  
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the menu item to delete",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Menu deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Menu deleted successfully"),
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
 *         description="Menu not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Menu not found"),
 *             @OA\Property(property="message", type="string", example="The menu item with the specified ID was not found"),
 *         )
 *     ),
 * )
 * 
 
 */

 



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
            $menu->category()->update(['category_name' => $req->category_name ?? $menu->category->category_name]);
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
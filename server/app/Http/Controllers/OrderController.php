<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Post(
 *     path="/api/create-order",
 *     summary="Insert a new order",
 *     tags={"Order"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Order details",
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(property="quantity", type="integer", example=2),
 *                 @OA\Property(property="phoneNumber", type="string", example="1234567890"),
 *                 @OA\Property(property="address", type="string", example="123 Main St"),
 *                 @OA\Property(property="name", type="string", example="Burger"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Order created with success"),
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
 *         description="Food item not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="This food item doesn't exist"),
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
 *     path="/api/getAllOrders",
 *     summary="Get all orders with details",
 *     tags={"Order"},
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="id", type="integer", example=1),
 *                 @OA\Property(property="quantity", type="integer", example=2),
 *                 @OA\Property(property="phone_number", type="string", example="1234567890"),
 *                 @OA\Property(property="address", type="string", example="123 Main St"),
 *                 @OA\Property(
 *                     property="menu",
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Burger"),
 *                     @OA\Property(property="price", type="number", format="float", example=10.99),
 *                 ),
 *                 @OA\Property(
 *                     property="user",
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="John Doe"),
 *                     @OA\Property(property="email", type="string", example="john@example.com"),
 *                 ),
 *                 @OA\Property(
 *                     property="bill",
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="total_amount", type="number", format="float", example=21.98),
 *                 )
 *             )
 *         )
 *     ),
 * )
 * 
 * * @OA\Delete(
 *     path="/api/deleteOrder/{id}",
 *     summary="Delete an order",
 *     tags={"Order"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the order to delete",
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order deleted successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Order deleted with success"),
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
 *         description="Order not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Order doesnt exist"),
 *         )
 *     ),
 * )
 * 
 * * @OA\Patch(
 *     path="/api/updateOrder/{id}",
 *     summary="Update an order",
 *     tags={"Order"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the order to update",
 *         @OA\Schema(
 *             type="integer",
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Order data",
 *         @OA\JsonContent(
 *             @OA\Property(property="quantity", type="integer", example=5),
 *             @OA\Property(property="phoneNumber", type="string", example="1234567890"),
 *             @OA\Property(property="address", type="string", example="123 Street, City"),
 *             @OA\Property(property="name", type="string", example="Burger"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Order updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Order Updated with success"),
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
 *         response=403,
 *         description="Forbidden",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Menu not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="The menu item does not exist"),
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
 * * @OA\Get(
 *     path="/api/index1",
 *     summary="Get orders for the authenticated user",
 *     tags={"Order"},
 *  
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/order"),
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
 * 
 * * @OA\Get(
 *     path="/api/getOrderById/{id}",
 *     summary="Get an order by ID",
 *     tags={"Order"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the order",
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/order"),
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
 *         description="Order not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Order doesn't exist"),
 *         )
 *     ),
 * )
 * 
 */

class OrderController {

    function insertOrder(Request $req) {

        $validate = Validator::make($req->all(),[
            'quantity' => 'required|numeric',
        ]);

        if($validate->fails()) {
            return response()->json([
            'message' => $validate->errors()->first(),
            'status' => false],422);
        }

        if(!Gate::allows('create-order')) {
            return response()->json(['message'=>'Unauthorized'],401);
        }

        $user = Auth::user();

    

        $user->phoneNumber = $req->input('phoneNumber');
        $user->address = $req->input('address');
        $user->save();

       $menu = Menu::where('name',$req->name)->first();

       if(!$menu) {
        return response()->json(['message' => 'This food item doesnt exist'],404);
       }



        $order = Order::create([
            'quantity' => $req->quantity,
            'phone_number' => $req->input('phoneNumber'),
            'address' => $req->input('address'),
            
        ]);

        $order->user()->associate($user);
        $order->menu()->associate($menu);

        $order->save();

        return response()->json(['message' => 'Order created with success'],200);


    }

    function getAllOrders() {

        $orders = Order::with('menu', 'user','bill')->get();

        foreach($orders as $order) {
            $total_amount = $order->menu->price * $order->quantity;

            $bill = new Bill([
                'total_amount' => $total_amount
            ]);

            $order->bill()->associate($bill);
            $bill->order()->save($order);
           
        }

       
        
        
        return response()->json($orders, 200);
    }

    function getOrderById(Request $req,$id) {

        $order = Order::with('user','menu')->find($id);

       if(!$order) {
        return response()->json(['message' => 'Order doesnt exist'],404);
       }

       return response()->json($order,200);
    }




    function destroy(Request $req,$id) {
        $order = Order::find($id);

        if(!$order) {
            return response()->json(['message'=>'Order doesnt exist'],404);
        }

        if(!Gate::allows('manage-order',$order)) {
            return response()->json(['message'=> 'Unauthorized'],401);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted with success'],200);
    }



    function updateOrder(Request $req,$id) {

        $validate = Validator::make($req->all(),[
            'quantity' => 'sometimes|numeric'
        ]);

        if($validate->fails()) {
            return response()->json(['message' => $validate->errors()->first(),'status' => false],422);
        }

        $order = Order::findorFail($id);

        if(!Gate::allows('manage-order',$order)) {
            return response()->json(['message'=>'Unauthorized'],403);
        }

        $order->update([
            'quantity' => $req->quantity ?? $order->quantity
        ]);

        if($req->has('phoneNumber')) {
            $order->user()->update(['phoneNumber'=> $req->phoneNumber ?? $order->user->phoneNumber]);
        }

        if($req->has('address')) {
            $order->user()->update(['address' => $req->address ?? $order->user->address]);
        }

        if($req->has('name')) {
            $menu = Menu::where('name', $req->name)->first();
            if (!$menu) {
                return response()->json(['message' => 'The menu item does not exist'], 404);
            }
            $order->menu_id = $menu->id;
        }
        

        return response()->json(['message'=>'Order Updated with success'],200);

    }


     function index () {
        $user = Auth::user();

        $orders = Order::where('user_id',$user->id)->with('menu','user','bill1')->get();
        return response()->json($orders,200);
     }
}



?>
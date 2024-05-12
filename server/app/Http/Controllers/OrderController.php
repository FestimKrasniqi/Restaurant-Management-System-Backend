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
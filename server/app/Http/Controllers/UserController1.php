<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Auth;





class UserController1 {
   
    function register(Request $req) {

     $validator = Validator::make($req->all(),[
        'first' => 'required|string|max:255',
        'last' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password'=> 'required|min:8',
        'confirm_password'=>'required|same:password'
     ]);

     if($validator->fails()) {
        return response()->json([
            'message'=>'Registration',
            'errors'=>$validator->errors()
        ],422);
     }

     $user = User::create([
        'first'=>$req->first,
        'last'=>$req->last,
        'email'=>$req->email,
        //'role'=>$req->role,
        'password'=>Hash::make($req->password),
        'confirm_password'=>Hash::make($req->confirm_password)
     ]);
       
        
      

        return response()->json([
            'message'=>'Registration successfull',
            'data'=>$user,
            'status'=> true,
            'token'=> $user->createToken("API_TOKEN")->plainTextToken

        ],200);
    


    }


    function Login(Request $req) {

        $validate = Validator::make($req->all(),[
         "email" => 'required|email',
         "password" => 'required'
        ]);

        if($validate->fails()) {
            return response()->json([
            "error"=>$validate->errors()->first(),
            "message"=>'validation error',
            "status"=> false
        
        ],422);
        }

        if(!Auth::attempt($req->only(["email","password"]))) {
            return response()->json([
                "status"=> false,
                'message'=> "Email or Password is incorrect"
            ],401);
        }
    

        $user = User::where('email',$req->email)->first();
        /*if(!$user || !Hash::check($req->password,$user->password)) {
            return ["error" => "Email or password is incorrect"];
        }*/
        return response()->json([
            "status"=> true,
            "message"=> "User logged successfully",
            "token" => $user->createToken("API_TOKEN")->plainTextToken

        ], 200);

    }
}

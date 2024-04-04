<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;



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
            'data'=>$user

        ],200);
    


    }
}

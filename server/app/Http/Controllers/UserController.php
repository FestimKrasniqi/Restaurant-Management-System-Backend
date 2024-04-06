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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;







class UserController {
   
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

    function Forgot(Request $req) {
        $validate = Validator::make($req->all(),[
          "email" => "required|email|exists:users",
        ]);

        if($validate->fails()) {
            return response()->json([
                "error" => $validate->errors()->first(),
                "message" => "validation error",
                "status" => false
            ],422);
        }

        $token = Str::random(64);

        $status = Password::sendResetLink($req->only('email'));

        return $status === Password::RESET_LINK_SENT
                    ? response()->json(['message' => 'Reset link sent to your email'], 200)
                    : response()->json(['message' => 'Unable to send reset link'], 400);
    
    }

    function Reset(Request $req) {
        $validate = Validator::make($req->all(),[
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|confirmed|min:8',
            'confirm_password' => 'required|same:password'
        ]);

        if($validate->fails()) {
            return response()->json([
                "error" => $validate->errors()->first(),
                "message" => "validation error",
                "status" => false
            ],422);
        }

        $status = Password::reset(
            $req->only('email', 'password', 'confirm_password', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? response()->json(['status' => __($status)])
                    : response()->json(['status' => __($status)], 400);
    }
}

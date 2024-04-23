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
use App\Mail\CustomResetPasswordNotification;
use Illuminate\Support\Facades\Cookie;






class UserController{
   
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
    
        

       
        $user = Auth::user();
      
        $token = $user->createToken("API_TOKEN")->plainTextToken;
        $cookie = cookie('jwt',$token,68*24);

        return response()->json([
            "status"=> true,
            "message"=> "User logged successfully",
            "token" => $token,
            "role" => $user->role

        ], 200)->withCookie($cookie);

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

   
    $user = User::where('email', $req->email)->first();
    $user->notify(new CustomResetPasswordNotification($token));

    return response()->json(['message' => 'Reset link sent to your email',
            "token" => $user->createToken("forgot")->plainTextToken

], 200);

    
    }

    function Reset(Request $req) {
        $validate = Validator::make($req->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password'
        ]);
    
        if ($validate->fails()) {
            return response()->json([
                "error" => $validate->errors()->first(),
                "message" => "Validation error",
                "status" => false
            ], 422);
        }
    
        $user = User::where('email', $req->input('email'))->first();
    
        if (!$user) {
            return response()->json([
                "error" => "User not found",
                "message" => "We couldn't find a user with that email address",
                "status" => false
            ], 400);
        }
    
        
    
        $user->password = Hash::make($req->input('password'));
        $user->save();
    
        return response()->json([
            'status' => 'Password successfully updated',
            'token' => $user->createToken("reset-password")->plainTextToken
        ], 200);
    }

    public function User() {
        if(Auth::check()) {
            $user = Auth::user();

            if($user->role === 'user') {
                return response()->json($user,200);
            }
        }
        return response()->json([
         "error"=>"Unauthorized"
        ],401);
    }

    public function logout(Request $request)
    {
        $cookie = Cookie::forget('jwt');
    
        return response()->json([
            'message' => 'Success'
        ])->withCookie($cookie);
    }

    function admin(Request $request) {
        if(Auth::check()) {
            $admin = Auth::user();
            
            if($admin->role === 'admin') {
                return response()->json($admin,200);
            }
        }

       return response()->json([
        "error"=>"Unauthorized"
       ],401);
    }

    public function allUsers()
    {
       
        $users = User::all();

        foreach ($users as $user) {
           
            $user->is_active = $user->active == 1 ? true : false;
        }

        return response()->json(['users' => $users]);
    }

}

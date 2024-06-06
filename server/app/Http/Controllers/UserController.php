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


/**
 * @OA\Post(
 *     path="/api/SignUp",
 *     summary="User registration",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"first", "last", "email", "password", "confirm_password"},
 *             @OA\Property(property="first", type="string"),
 *             @OA\Property(property="last", type="string"),
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="password", type="string", format="password"),
 *             @OA\Property(property="confirm_password", type="string", format="password"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful registration",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="data", ref="#/components/schemas/users"),
 *             @OA\Property(property="status", type="boolean"),
 *             @OA\Property(property="token", type="string"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="errors", type="object"),
 *         ),
 *     ),
 * )
 *
 * @OA\Post(
 *     path="/api/Login",
 *     summary="User Login",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email","password"},
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="password", type="string", format="password"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful Login",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="data", ref="#/components/schemas/users"),
 *             @OA\Property(property="status", type="boolean"),
 *             @OA\Property(property="token", type="string"),
 *         ),
 *     ),    
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="errors", type="object"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthenticated"),
 *         ),
 *     ),
 * )
 * * Forget user password.
 * 
 * @OA\Post(
 *     path="/api/Forgot-Password",
 *     summary="Forget user password",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email"},
 *             @OA\Property(property="email", type="string", format="email"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password reset email sent",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="Password reset email sent"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="User not found"),
 *             @OA\Property(property="message", type="string", example="We couldn't find a user with that email address"),
 *             @OA\Property(property="status", type="boolean", example=false),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string"),
 *             @OA\Property(property="message", type="string", example="Validation error"),
 *             @OA\Property(property="status", type="boolean", example=false),
 *         ),
 *     ),
 * )
 * 
 * * Reset user password.
 * 
 * @OA\Post(
 *     path="/api/Reset-Password",
 *     summary="Reset user password",
 *     tags={"Authentication"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password", "confirm_password"},
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="password", type="string", format="password"),
 *             @OA\Property(property="confirm_password", type="string", format="password"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Password successfully updated",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="Password successfully updated"),
 *             @OA\Property(property="token", type="string", description="Authentication token"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="User not found"),
 *             @OA\Property(property="message", type="string", example="We couldn't find a user with that email address"),
 *             @OA\Property(property="status", type="boolean", example=false),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string"),
 *             @OA\Property(property="message", type="string", example="Validation error"),
 *             @OA\Property(property="status", type="boolean", example=false),
 *         ),
 *     ),
 * )
 * 
 * 
 * * Logout user and invalidate token.
 * 
 * @OA\Post(
 *     path="/api/logout",
 *     summary="Logout user",
 *     tags={"Authentication"},
 *    
 *     @OA\Response(
 *         response=200,
 *         description="Logout successful",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Logout successful"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized"),
 *         ),
 *     ),
 * )
 * 
 * /**
 * Get all users.
 * 
 * @OA\Get(
 *     path="/api/users",
 *     summary="Get all users",
 *     tags={"Users"},
 *
 *     @OA\Response(
 *         response=200,
 *         description="List of users",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/users"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Unauthorized"),
 *         ),
 *     ),
 * )
 * 
 * * @OA\Get(
 *     path="/api/user1",
 *     summary="Get authenticated user information",
 *     tags={"Users"},
 *    
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/user"),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Unauthorized"),
 *         )
 *     ),
 * )
 * 
 *  @OA\Get(
 *     path="/api/admin",
 *     summary="Get authenticated admin information",
 *     tags={"Users"},
 *    
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(ref="#/components/schemas/user"),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Unauthorized"),
 *         )
 *     )
 * )
 */
 

 


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
        $token = $request->user()->currentAccessToken();

   
      if ($token) {
        $token->delete();
       }
       
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

        return response()->json($users);
    }

}

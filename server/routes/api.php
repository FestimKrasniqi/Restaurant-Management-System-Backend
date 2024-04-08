<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/SignUp',[UserController::class,'register']);

Route::post('/Login',[UserController::class,'Login']);

Route::post('/Forgot-Password',[UserController::class,'Forgot']);
Route::post('/Reset-Password',[UserController::class,'Reset']);

Route::middleware("auth:sanctum")->group(function (){
Route::get('/user1',[UserController::class,'User']);
Route::post('/logout',[UserController::class,'logout']);
Route::get('/admin',[UserController::class,'admin']);
});

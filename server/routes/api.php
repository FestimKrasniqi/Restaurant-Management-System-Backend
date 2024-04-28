<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\UserMiddleware;



Route::post('/SignUp',[UserController::class,'register']);

Route::post('/Login',[UserController::class,'Login']);

Route::post('/Forgot-Password',[UserController::class,'Forgot']);
Route::post('/Reset-Password',[UserController::class,'Reset']);
Route::get('/users', [UserController::class, 'allUsers']);


Route::middleware("auth:sanctum")->group(function (){

    Route::post('/logout',[UserController::class,'logout']);
    
    Route::middleware(UserMiddleware::class)->group(function () {
    Route::get('/user1',[UserController::class,'User']);
});



Route::middleware(Admin::class)->group(function () {

    Route::get('/admin', [UserController::class, 'admin']);
    Route::get('/users', [UserController::class, 'allUsers']);
});
});
    




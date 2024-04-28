<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\UserMiddleware;
use App\Http\Controllers\MenuController;


Route::post('/SignUp',[UserController::class,'register']);

Route::post('/Login',[UserController::class,'Login']);

Route::post('/Forgot-Password',[UserController::class,'Forgot']);
Route::post('/Reset-Password',[UserController::class,'Reset']);
Route::get('/users', [UserController::class, 'allUsers']);


Route::middleware("auth:sanctum")->group(function (){

    Route::post('/logout',[UserController::class,'logout']);
    
    Route::middleware(UserMiddleware::class)->group(function () {
    Route::get('/user1',[UserController::class,'User']);
    Route::get('/menus/{categoryName}', [MenuController::class, 'getMenuByCategory']);
});



Route::middleware(Admin::class)->group(function () {

    Route::get('/admin', [UserController::class, 'admin']);
    Route::get('/users', [UserController::class, 'allUsers']);
    Route::post('/create-menu',[MenuController::class,'insertMenu']);
    Route::get('/allMenus',[MenuController::class,'allMenu']);
});
});
    




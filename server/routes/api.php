<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Admin;
use App\Http\Middleware\UserMiddleware;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SupplierController;


Route::post('/SignUp',[UserController::class,'register']);

Route::post('/Login',[UserController::class,'Login']);


Route::post('/Forgot-Password',[UserController::class,'Forgot']);
Route::post('/Reset-Password',[UserController::class,'Reset']);



Route::middleware("auth:sanctum")->group(function (){

    Route::post('/logout',[UserController::class,'logout']);
    
    
    Route::middleware(UserMiddleware::class)->group(function () {
    Route::get('/user1',[UserController::class,'User']);
    Route::get('/menus/{categoryName}', [MenuController::class, 'getMenuByCategory']);
    Route::post('/create-Review',[ReviewController::class,'insertReview']);
});



Route::middleware(Admin::class)->group(function () {

    Route::get('/admin', [UserController::class, 'admin']);
    Route::get('/users', [UserController::class, 'allUsers']);
    Route::patch('/updateMenu/{id}',[MenuController::class,'updateMenu']);
    Route::post('/create-menu',[MenuController::class,'insertMenu']);
    Route::get('/allMenus',[MenuController::class,'allMenu']);
    Route::delete('/delete-menu/{id}',[MenuController::class,'destroy']);
    Route::get('/menu/{id}',[MenuController::class,'getMenuById']);
    Route::post('/add-staff',[StaffController::class,'insertStaff']);
    Route::get('/staff/{id}',[StaffController::class,'getStaffById']);
    Route::get('/allStaff',[StaffController::class,'getAllStaff']);
    Route::patch('/updateStaff/{id}',[StaffController::class,'updateStaff']);
    Route::delete('/deleteStaff/{id}',[StaffController::class,'destroy']);
    Route::post('/insertTable',[TableController::class,'insert']);
    Route::get('/getAllTables',[TableController::class,'getAllTables']);
    Route::get('/getTableById/{id}',[TableController::class,'getTableById']);
    Route::patch('/updateTable/{id}',[TableController::class,'updateTable']);
    Route::delete('/deleteTable/{id}',[TableController::class,'destroy']);
    Route::get('/getReviews',[ReviewController::class,'getAllReviews']);
    Route::post('/insertSupplier',[SupplierController::class,'insertSupplier']);
    Route::get('/getSupplier',[SupplierController::class,'getSupplier']);
    Route::delete('/deleteSupplier/{id}',[SupplierController::class,'destroy']);
    Route::get('/getSupplierById/{id}',[SupplierController::class,'getSupplierById']);
    Route::patch('/updateSupplier/{id}',[SupplierController::class,'updateSupplier']);
});


});
    




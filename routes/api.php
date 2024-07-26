<?php

use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:api');

Route::get('test',function(){
    return response(['working...']);
});

Route::post('user/register',[UserController::class,'register']);
Route::post('user/login',[UserController::class,'login']);
Route::post('user/changepassword',[UserController::class,'changepassword']);

Route::middleware('auth:api')->group(function(){
    Route::get('user/{id}',[UserController::class,'getuser']);
    Route::post('user/logout',[UserController::class,'logout']);
    //--->post
    Route::post('post/create',[PostController::class,'create']);
    Route::get('post/all',[PostController::class,'getall']);
    Route::get('post/{id}',[PostController::class,'getone']);
    Route::put('post/update/{id}',[PostController::class,'update']);
    Route::delete('post/delete/{id}',[PostController::class,'delete']);
});

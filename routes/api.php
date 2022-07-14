<?php

use App\Http\Controllers\BprtmaController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function() {
    Route::post('/login',[UserController::class, 'login']);
    Route::post('/logout',[UserController::class,'logout'])->middleware(['auth:api']);

    //get Something
    Route::get('/',[UserController::class,'index'])->middleware(['auth:api']);

    //show all User
    Route::get('/user',[UserController::class,'show_all'])->middleware(['auth:api']);
    
    //add User
    Route::post('/user',[UserController::class,'store'])->middleware(['auth:api']);

    //show User
    Route::post('/user/{id}',[UserController::class,'show'])->middleware(['auth:api']);

    //edit User
    Route::put('/user/{id}',[UserController::class,'update'])->middleware(['auth:api']);

    //delete User
    Route::delete('/user/{id}',[UserController::class,'destroy'])->middleware(['auth:api']);
    
    //cek dulu
    // Route::resource('user', [UserController::class]);
});

Route::get('/bpr',[BprtmaController::class, 'index'])->middleware('auth:api');
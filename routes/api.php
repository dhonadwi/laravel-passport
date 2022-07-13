<?php

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'v1'], function() {
    Route::post('/login',[UserController::class, 'login']);
    Route::post('/logout',[UserController::class,'logout'])->middleware(['auth:api']);

    //get Something
    Route::get('/',[UserController::class,'index'])->middleware(['auth:api']);

    //add User
    Route::post('/user',[UserController::class,'store'])->middleware(['auth:api']);

    //show User
    Route::post('/user/{id}',[UserController::class,'show'])->middleware(['auth:api']);

    //edit User
    Route::put('/user/{id}',[UserController::class,'update'])->middleware(['auth:api']);
    
    //cek dulu
    // Route::resource('user', [UserController::class]);
});

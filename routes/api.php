<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\UserController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function(){
//    Route::get('member/test',[\App\Http\Controllers\Api\v1\UserCon::class,'test']);
    Route::post('member/add',[UserController::class,'mailRegister']);
//    Route::post('/verify', 'Api\V1\EmailAuthController@verify');
    Route::middleware('auth.jwt')->group(function(){
        Route::put('member/update/{id)', [UserController::class,'edit']);
        Route::delete('member/delete/{id}',[UserController::class,'destroy']);
        Route::get('member/list',[UserController::class,'index']);
    });
});

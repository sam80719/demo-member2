<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\VerifyController;
use App\Http\Controllers\Api\v1\LoginController;

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


Route::prefix('member/v1')->group(function () {

//    Route::get('sendMail', function () {
//        \Mail::to('abc@abc.com')->send(new \App\Mail\MyTestMail());
//    });
    Route::post('register', [UserController::class, 'mailRegister']);
    Route::post('verify', [UserController::class, 'verifyMail'])->name('api.member.verify');
    Route::post('login', [LoginController::class, 'store']);
    Route::middleware('auth.jwt')->group(function () {
        Route::put('update/{id)', [UserController::class, 'edit']);
        Route::delete('delete/{id}', [UserController::class, 'destroy']);
        Route::get('list', [UserController::class, 'index']);
    });
});

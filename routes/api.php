<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\UserController;
use App\Http\Controllers\Api\v1\LoginController;
use App\Http\Controllers\Auth\AuthController;

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

Route::group([
//    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/verify', [AuthController::class, 'verifyMail'])->name('api.member.verify');;
    Route::post('/register', [AuthController::class, 'mailRegister']);
    Route::put('/update/{id)', [AuthController::class, 'editMember']);
    Route::delete('/delete/{id}', [AuthController::class, 'destroy']);
    Route::get('/list', [AuthController::class, 'index']);
});

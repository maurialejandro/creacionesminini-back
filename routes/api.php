<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::middleware('auth:sanctum')->group(function () {
  // Authenticated Routes in api.php
  Route::get('user',[UserController::class, 'getUsers']);
});

Route::post('login',[UserController::class, 'login']);
Route::post('register', [Usercontroller::class, 'register']);
Route::get('user', [UserController::class, 'getUsers']);



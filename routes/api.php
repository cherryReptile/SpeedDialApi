<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

//Public routes
Route::prefix('auth')->controller(AuthController::class)->group(function(){
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});
//Private routes
Route::middleware('auth:sanctum')->group(function (){
    Route::post('/logout', [AuthController::class, 'logout']);
});

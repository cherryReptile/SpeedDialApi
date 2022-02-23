<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

//Public routes
Route::prefix('auth')->controller(AuthController::class)->group(function(){
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
});
//Private routes
Route::middleware('auth:sanctum')->group(function (){
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::prefix('category')->controller(CategoryController::class)->group(function(){
        Route::post('/', 'create')->name('create.category');
        Route::get('/', 'show')->name('get.category');
        Route::patch('/{category}', 'update')->name('update.category');
        Route::delete('/{category}', 'delete')->name('delete.category');
    });
});

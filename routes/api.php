<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SpeedDialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

//Public routes
Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
});
//Private routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::prefix('category')->controller(CategoryController::class)->group(function () {
        Route::post('/', 'create')->name('create.category');
        Route::get('/{category}', 'show')->name('get.category');
        Route::patch('/{category}', 'update')->name('update.category');
        Route::delete('/{category}', 'delete')->name('delete.category');
    });
    Route::prefix('dial')->controller(SpeedDialController::class)->group(function () {
        Route::post('/{category}', 'create')->name('create.dial');
        Route::get('/{dial}', 'show')->name('get.dial');
        Route::patch('/{dial}', 'update')->name('update.dial');
        Route::delete('/{dial}', 'delete')->name('delete.dial');
    });
    Route::prefix('speed/dials')->controller(SpeedDialController::class)->group(function () {
        Route::get('/', 'SpeedDials')->name('get.speed.dials');
    });
});

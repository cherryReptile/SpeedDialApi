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
    Route::prefix('category')->group(function () {
        Route::post('/', [CategoryController::class, 'create'])->name('create.category');
        Route::get('/{id}', [CategoryController::class, 'show'])->name('get.category');
        Route::get('/', [CategoryController::class, 'all'])->name('get.all.category');
        Route::patch('/{id}', [CategoryController::class, 'update'])->name('update.category');
        Route::delete('/{id}', [CategoryController::class, 'delete'])->name('delete.category');
        Route::post('/{id}/dial', [SpeedDialController::class, 'create'])->name('create.dial');
    });
    Route::prefix('dial')->group(function () {
        Route::get('/{id}', [SpeedDialController::class, 'show'])->name('get.dial');
        Route::get('/', [SpeedDialController::class, 'all'])->name('all.dials');
        Route::patch('/{id}', [SpeedDialController::class, 'update'])->name('update.dial');
        Route::delete('/{id}', [SpeedDialController::class, 'delete'])->name('delete.dial');
    });
});

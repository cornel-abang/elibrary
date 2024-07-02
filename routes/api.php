<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [AuthenticationController::class, 'register'])->name('register');
    Route::post('login', [AuthenticationController::class, 'login'])->name('login');
    Route::post('logout', [AuthenticationController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('refresh', [AuthenticationController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::get('me', [AuthenticationController::class, 'me'])->middleware('auth:api')->name('me');
});

Route::middleware('auth:api')->group(function () {
    Route::apiResource('books', BookController::class);
    Route::apiResource('authors', AuthorController::class);
});


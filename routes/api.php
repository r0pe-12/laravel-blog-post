<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Middleware\CheckToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => CheckToken::class], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
    });
});


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/posts', PostController::class);
    Route::apiResource('/comments', CommentController::class)->only(['store', 'update', 'destroy']);
});


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

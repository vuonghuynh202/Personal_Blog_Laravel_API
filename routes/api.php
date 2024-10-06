<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::apiResource('/users', UserController::class);
Route::apiResource('/categories', CategoryController::class);
Route::apiResource('/menus', MenuController::class);
Route::apiResource('/tags', TagController::class);
Route::apiResource('/settings', SettingController::class);
Route::apiResource('/posts', PostController::class);

Route::controller(AuthController::class)->group(function() {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout')->middleware('auth:api');
    Route::post('refresh', 'refresh')->middleware('auth:api');
});

Route::controller(CommentController::class)->group(function() {
    Route::get('comments/{id}', 'index');
    Route::post('comments/{id}', 'store')->middleware('auth:api');
    Route::put('comments/{id}', 'update')->middleware('auth:api');
    Route::delete('comments/{id}', 'destroy')->middleware('auth:api');
});

Route::controller(LikeController::class)->group(function() {
    Route::post('like/{id}', 'like')->middleware('auth:api');
    Route::post('unlike/{id}', 'unlike')->middleware('auth:api');
});




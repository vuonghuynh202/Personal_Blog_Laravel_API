<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\CategoryController as WebCategoryController;
use App\Http\Controllers\Web\PostController as WebPostController;
use App\Http\Controllers\Web\SearchController;
use App\Http\Controllers\Web\TagController as WebTagController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/login', [AdminAuthController::class, 'showAdminLoginForm'])->name('admin.login');

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.index');
    
    Route::get('posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::get('posts/edit/{id}', [PostController::class, 'edit'])->name('posts.edit');

    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::get('users/edit/{id}', [UserController::class, 'edit'])->name('users.edit');
    
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
    
    Route::get('menus', [MenuController::class, 'index'])->name('menus.index');
    Route::get('menus/edit/{id}', [MenuController::class, 'edit'])->name('menus.edit');
    
    Route::get('tags', [TagController::class, 'index'])->name('tags.index');
    
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/{slug}', [WebPostController::class, 'details']);

Route::get('/cat/{slug}', [WebCategoryController::class, 'postsCategory']);

Route::get('/tag/{slug}', [WebTagController::class, 'postsTag']);

Route::get('/user/login', [AuthController::class, 'login'])->name('login');

Route::get('/user/register', [AuthController::class, 'register'])->name('register');

Route::get('/posts/search', [SearchController::class, 'search'])->name('search');

Route::get('/user/{id}', [AuthController::class, 'userProfile'])->name('user.profile');

<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\Contact_UsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ThemeController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', [AuthController::class, 'index'])->name('login');


Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');

    Route::get('/posts', [PostsController::class, 'index'])->name('posts');
    Route::get('/posts/add', [PostsController::class, 'addView'])->name('posts.add');
    Route::get('/posts/{id}', [PostsController::class, 'editView'])->name('posts.edit');

    Route::get('/page', [PageController::class, 'index'])->name('page');

    Route::get('/contact-us', [Contact_UsController::class, 'index'])->name('contact-us');

    Route::get('/banner', [BannerController::class, 'index'])->name('banner');

    Route::get('/theme', [ThemeController::class, 'index'])->name('theme');

    Route::get('/users', [UsersController::class, 'index'])->name('users');
    Route::get('/users/add', [UsersController::class, 'add'])->name('users.add');
    Route::get('/users/edit/{id}', [UsersController::class, 'detail'])->name('users.edit');
});

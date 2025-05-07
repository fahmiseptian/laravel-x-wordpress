<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::post('/post-update-status', [PostsController::class, 'updatePostStatus']);
Route::post('/post-add', [PostsController::class, 'addPost']);
Route::post('/post-update', [PostsController::class, 'updatePost']);
Route::delete('/post-delete/{id}', [PostsController::class, 'deletePost']);
Route::post('/posts/update-cover', [PostsController::class, 'updateCover']);



Route::post('/page-update', [PageController::class, 'UpdatePage']);
Route::delete('/page-delete/{id}', [PageController::class, 'DeletePage']);

Route::post('/banners/update', [BannerController::class, 'updateBanners']);
Route::delete('/banners', [BannerController::class, 'deleteBanner']);

Route::post('/users/register', [UsersController::class, 'register']);
Route::post('/users/update-status', [UsersController::class, 'updateStatus']);
Route::put('/users/change-password', [UsersController::class, 'changePassword']);
Route::POST('/users/update', [UsersController::class, 'update']);

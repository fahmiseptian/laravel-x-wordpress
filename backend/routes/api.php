<?php

use App\Http\Controllers\PageController;
use App\Http\Controllers\PostsController;
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

Route::post('/post-update-status', [PostsController::class, 'updatePostStatus']);
Route::post('/post-add', [PostsController::class, 'addPost']);
Route::delete('/post-delete/{id}', [PostsController::class, 'deletePost']);


Route::post('/page-update', [PageController::class, 'UpdatePage']);
Route::delete('/page-delete/{id}', [PageController::class, 'DeletePage']);

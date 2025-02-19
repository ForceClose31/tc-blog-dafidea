<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:Admin'])->group(function () {
    Route::post('/posts', [PostController::class, 'store']);
    Route::post('/posts/{id}', [PostController::class, 'update']);
    Route::delete('/posts/{id}', [PostController::class, 'destroy']);

    Route::get('/dashboard', function () {
        return response()->json(['message' => 'Admin Dashboard'], 200);
    });
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/password/reset', [AuthController::class, 'sendResetPasswordLink'])->name('password.reset');
Route::post('/posts/{postId}/comments', [CommentController::class, 'store']);
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{id}', [PostController::class, 'show']);

Route::post('/login', [AuthController::class, 'login']);

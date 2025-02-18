<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('welcome');
});
Route::get('/logout', function () {
    return view('welcome');
});

Route::get('/posts/{any}', function () {
    return view('welcome');
})->where('any', '.*');


Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();
    if (!Auth::check() || !$user->hasRole('Admin')) {
        abort(403, 'Forbidden');
    }
    return view('welcome');
});

<?php
use App\Http\Controllers\PostController;
use Illuminate\Routing\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'abac:view posts,time_based'])->group(function () {
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
});
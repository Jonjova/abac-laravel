<?php
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth', 'abac:view posts,time_based'])->group(function () {
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('posts.index');
        Route::get('/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('/', [PostController::class, 'store'])->name('posts.store');
        Route::get('/{post}', [PostController::class, 'show'])->name('posts.show');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    });

    //agrupa con un prefix las rutas de los usuarios
    Route::prefix('users')->group(function () {
        Route::get('/', 'UsuariosController@index')->name('users.index');
        Route::get('/create', 'UsuariosController@create')->name('users.create');
        Route::post('/', 'UsuariosController@store')->name('users.store');
        Route::get('/{user}', 'UsuariosController@show')->name('users.show');
        Route::get('/{user}/edit', 'UsuariosController@edit')->name('users.edit');
        Route::put('/{user}', 'UsuariosController@update')->name('users.update');
        Route::delete('/{user}', 'UsuariosController@destroy')->name('users.destroy');
    });

});




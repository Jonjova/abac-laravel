<?php
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth')->group(function () {
    // Rutas para el modulo de post
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index'])
            ->middleware('can:viewAny,App\Post')
            ->name('posts.index');
        Route::get('/create', [PostController::class, 'create'])
            ->middleware('can:create,App\Post')
            ->name('posts.create');
        Route::post('/', [PostController::class, 'store'])
            ->middleware('can:create,App\Post')
            ->name('posts.store');
        Route::get('/{post}', [PostController::class, 'show'])
            ->middleware('can:view,post')
            ->name('posts.show');
        Route::get('/{post}/edit', [PostController::class, 'edit'])
            ->middleware('can:update,post')
            ->name('posts.edit');
        Route::put('/{post}', [PostController::class, 'update'])
            ->middleware('can:update,post')
            ->name('posts.update');
        Route::delete('/{post}', [PostController::class, 'destroy'])
            ->middleware('can:delete,post')
            ->name('posts.destroy');
    });

    // Rutas para el modulo de usuarios
    Route::prefix('users')->group(function () {
        Route::get('/', 'UsuariosController@index')->middleware('can:viewAny,App\User')->name('users.index');
        Route::get('/create', 'UsuariosController@create')->middleware('can:create,App\User')->name('users.create');
        Route::post('/', 'UsuariosController@store')->middleware('can:create,App\User')->name('users.store');
        Route::get('/{user}', 'UsuariosController@show')->middleware('can:view,user')->name('users.show');
        Route::get('/{user}/edit', 'UsuariosController@edit')->middleware('can:update,user')->name('users.edit');
        Route::put('/{user}', 'UsuariosController@update')->middleware('can:update,user')->name('users.update');
        Route::delete('/{user}', 'UsuariosController@destroy')->middleware('can:delete,user')->name('users.destroy');

        // Rutas de permisos
        Route::middleware('can:assignPermissions,user')->group(function () {
            Route::get('/{user}/permissions', 'UsuariosController@permissions')->name('users.permissions');
            Route::post('/{user}/permissions', 'UsuariosController@assignPermissions')->name('users.permissions.assign');
        });

        Route::delete('/{user}/permissions/{permission}', 'UsuariosController@revokePermissions')->middleware('can:revokePermission,user,permission')->name('users.permissions.revoke');
    });
});

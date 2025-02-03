<?php

use App\Http\Controllers\EndpointController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\UserAuthController;
use App\Middlewares\ApiMiddleware;
use App\Middlewares\PanelMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('/')
    ->group(function () {
        Route::get('/', [PublicController::class, 'index'])->name('home');
        Route::get('detail/{id}', [PublicController::class, 'detail'])
            ->where('id', '[0-9]+')
            ->name('detail');
        Route::get('stalice', [PublicController::class, 'bestOff'])->name('best-of');

        Route::get('login', [UserAuthController::class, 'login'])->name('login');
        Route::get('logout', [UserAuthController::class, 'logout'])->name('logout');
        Route::get('register', [UserAuthController::class, 'register'])->name('register');

        Route::prefix('panel')->controller(PanelController::class)
            ->middleware([PanelMiddleware::class])
            ->group(function () {
                Route::get('/', 'panel')->name('panel');
                Route::get('profil', 'profile')->name('profile');
                Route::get('pridat', 'addBook')->name('add-book');
                Route::get('uprava/{id}', 'editBook')
                    ->where('id', '[0-9]+')
                    ->name('edit-book');
            });
    });

Route::prefix('/api/v1')->controller(EndpointController::class)
    ->middleware([ApiMiddleware::class])
    ->group(function () {

        Route::post('login', 'login')->withoutMiddleware([ApiMiddleware::class]);
        Route::post('logout', 'logout')->withoutMiddleware([ApiMiddleware::class]);
        Route::post('register', 'register')->withoutMiddleware([ApiMiddleware::class]);
        Route::options('passcheck', 'checkPassword')->withoutMiddleware([ApiMiddleware::class]);


        // create
        Route::post('/', 'create');
        Route::post('/comments', 'addComment');
        // read
        Route::get('/', 'getAll');
        Route::get('/{id}', 'get')->where('id', '[0-9]+');
        Route::get('/comments/{bookId}', 'getComments')->where('bookId', '[0-9]+');
        // update
        Route::put('/{id}', 'update')->where('id', '[0-9]+');
        Route::put('/comments/{id}', 'updateComment')->where('id', '[0-9]+');
        // delete
        Route::delete('/{id}', 'delete')->where('id', '[0-9]+');
        Route::delete('/panel/profil', 'deleteProfile')->where('id', '[0-9]+');
        Route::delete('/comments/{id}', 'deleteComment');
    });

<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => Redirect::route('boards.index'));

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('boards')->name('boards.')->group(function () {
        Route::get('/', [BoardController::class, 'index'])->name('index');
        Route::get('create', [BoardController::class, 'create'])->name('create');
        Route::post('/', [BoardController::class, 'store'])->name('store');

        Route::prefix('/{board}')->group(function () {
            Route::get('/', [BoardController::class, 'show'])->name('show');
            Route::prefix('invitations')->name('invitations.')->group(function () {
                Route::get('/', [BoardController::class, 'invitations'])->name('index');
                Route::middleware('signed')->get('/accept', [BoardController::class, 'acceptInvitation'])->name('accept');
            });
            Route::get('/edit', [BoardController::class, 'edit'])->name('edit');
            Route::put('/', [BoardController::class, 'update'])->name('update');
            Route::delete('/', [BoardController::class, 'destroy'])->name('destroy');
        });
    });

    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::post('/store', [TaskController::class, 'store'])->name('store');
        Route::put('/{task}/update', [TaskController::class, 'update'])->name('update');
        Route::delete('/{task}/destroy', [TaskController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware('auth:sanctum')->post('/broadcasting/auth', function () {
    return Broadcast::auth(request());
});

require __DIR__.'/auth.php';

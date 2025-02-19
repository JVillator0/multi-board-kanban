<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('boards/reorder', [App\Http\Controllers\BoardController::class, 'reorder']);
Route::resource('boards', App\Http\Controllers\BoardController::class);

Route::get('tasks/reorder', [App\Http\Controllers\TaskController::class, 'reorder']);
Route::resource('tasks', App\Http\Controllers\TaskController::class)->except('create', 'edit', 'show');

Route::resource('comments', App\Http\Controllers\CommentController::class)->only('store', 'update', 'destroy');

Route::get('invitations/resend', [App\Http\Controllers\InvitationController::class, 'resend']);
Route::resource('invitations', App\Http\Controllers\InvitationController::class)->only('store', 'create', 'destroy');

Route::get('tasks/reorder', [App\Http\Controllers\Api\TaskController::class, 'reorder']);
Route::resource('tasks', App\Http\Controllers\Api\TaskController::class)->only('index', 'update');

Route::get('boards/reorder', [App\Http\Controllers\Api\BoardController::class, 'reorder']);

Route::resource('comments', App\Http\Controllers\Api\CommentController::class)->except('create', 'edit', 'show');

Route::get('invitations/resend', [App\Http\Controllers\Api\InvitationController::class, 'resend']);
Route::get('invitations/revoke', [App\Http\Controllers\Api\InvitationController::class, 'revoke']);
Route::resource('invitations', App\Http\Controllers\Api\InvitationController::class)->only('index', 'store');

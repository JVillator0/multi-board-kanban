<?php

use App\Http\Controllers\Api\BoardController;
use App\Http\Controllers\Api\InvitationController;
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

Route::get('boards/reorder', [BoardController::class, 'reorder']);

Route::get('invitations/resend', [InvitationController::class, 'resend']);
Route::get('invitations/revoke', [InvitationController::class, 'revoke']);
Route::resource('invitations', InvitationController::class)->only('index', 'store');

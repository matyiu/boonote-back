<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Notes part
    Route::resource('notes', NoteController::class)->except([
        'edit', 'create'
    ]);
    Route::post('notes/{id}/tags', [TagController::class, 'store']);
    Route::delete('notes/{id}/tags/{tagId}', [NoteController::class, 'detachTag']);
    Route::post('notes/{id}/tags/{tagId}', [NoteController::class, 'attachTag']);

    // User part
    Route::get('/profile', [UserController::class, 'show']);
    Route::put('/profile', [UserController::class, 'update']);
    Route::put('/profile/tags/{id}', [TagController::class, 'update']);
    Route::delete('/profile/tags/{id}', [TagController::class, 'destroy']);

    Route::delete('logout', [AuthController::class, 'logout']);
});

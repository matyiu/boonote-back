<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
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
Route::delete('logout', [AuthController::class, 'logout']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Notes part
    Route::resource('notes', NoteController::class)->except([
        'edit', 'create'
    ]);
    Route::prefix('notes')->group(function() {
        Route::post('{id}/tags', [CategoryController::class, 'store']);
        Route::delete('{id}/tags/{tagId}', [NoteController::class, 'detachCategory']);
        Route::post('{id}/tags/{tagId}', [NoteController::class, 'attachCategory']);
    });

    // User part
    Route::prefix('profile')->group(function() {
        Route::get('/', [UserController::class, 'show']);
        Route::put('/', [UserController::class, 'update']);
        Route::put('/tags/{id}', [CategoryController::class, 'update']);
        Route::delete('/tags/{id}', [CategoryController::class, 'destroy']);
    });
});

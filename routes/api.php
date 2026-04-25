<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\VideoController;

// Endpoint: /api/

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

// Route::get('/debug-db', [DebugController::class, 'checkDb']);

// Route::get('/mentor', [DebugController::class, 'checkMentor']);

// Route::apiResource('videos', VideoController::class);

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MentorController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public endpoints
Route::get('/competitions', [CompetitionController::class, 'index']);
Route::get('/competitions/{id}', [CompetitionController::class, 'show']);

// Route terproteksi (harus membawa token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);

    Route::get('/mentors', [MentorController::class, 'index']);
    Route::get('/mentors/{mentor}', [MentorController::class, 'show']);
});
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DebugController;
use App\Http\Controllers\VideoController;

// Endpoint: /api/

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/debug-db', [DebugController::class, 'checkDb']);

Route::get('/mentor', [DebugController::class, 'checkMentor']);

Route::apiResource('videos', VideoController::class);
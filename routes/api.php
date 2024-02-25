<?php

use App\Http\Controllers\GenerateAvatarController;
use App\Http\Controllers\GetAvatarController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/avatar/{generation}', GetAvatarController::class);
Route::post('/generate-avatar', GenerateAvatarController::class);

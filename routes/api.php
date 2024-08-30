<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth.jwt'])->group(function() {
    Route::post('/register/acc', [AuthController::class, 'create_new_account']);
    Route::post('/login', [AuthController::class, 'authenticated']);
});

Route::middleware(['parse.jwt'])->group(function() {
    Route::post('/forget_password', [AuthController::class, 'forget_password']);
    Route::post('/check_otp', [AuthController::class, 'check_otp']);
});

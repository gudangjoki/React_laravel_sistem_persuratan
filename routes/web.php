<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LetterManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('tasks.index');
});

// Route::post('/register/acc', [AuthController::class, 'create_new_account']);
Route::get('/register', [AuthController::class, 'register_page']);

Route::get('/otp', [AuthController::class, 'otp']);

Route::post('/api/letter', [LetterManagementController::class, 'create']);
// Route::post('/api/login', [LetterManagementController::class, 'authenticated']);

Route::get('/get-csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

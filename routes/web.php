<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('tasks.index');
});

Route::post('/register/acc', [AuthController::class, 'create_new_account']);
Route::get('/register', [AuthController::class, 'register_page']);

Route::get('/otp', [AuthController::class, 'otp']);

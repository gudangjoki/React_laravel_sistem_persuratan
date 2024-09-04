<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LetterManagementController;
use App\Http\Controllers\RoleManagementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware(['auth.jwt', 'cors'])->group(function() {
    Route::post('/register/acc', [AuthController::class, 'create_new_account']);
    Route::post('/login', [AuthController::class, 'authenticated']);
});

Route::middleware(['parse.jwt', 'cors'])->group(function() {
    Route::post('/check_otp', [AuthController::class, 'check_otp']);
    Route::post('/refresh_token', [AuthController::class, 'refresh_token']);
    Route::delete('/logout', [AuthController::class, 'logout']);

    Route::get('/keywords', [LetterManagementController::class, 'get_all_keywords']);
    Route::get('/letter/{uuid}', [LetterManagementController::class, 'view_detail']);
    Route::put('/letter/{uuid}', [LetterManagementController::class, 'edit_detail']);
    Route::post('/letter', [LetterManagementController::class, 'create_letter']);
    Route::get('/letters', [LetterManagementController::class, 'get_all_letters']);
    
    Route::get('/count_letter', [DashboardController::class, 'checkCountEachLetterType']);
    Route::get('/letters/{letter_id_type}', [DashboardController::class, 'getLetterByTypeParameterUrl']);

    Route::post('/role', [RoleManagementController::class], 'createNewRole');
    Route::get('/roles', [RoleManagementController::class, 'getAllRoles']);
    Route::get('/permissions', [RoleManagementController::class, 'getAllPermissions']);

    Route::get('/me', [DashboardController::class, 'getSelfAccountInformation']);
});

Route::middleware(['cors'])->group(function() {
    Route::post('/forget_password', [AuthController::class, 'forget_password']);
});


<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\talent\TalentController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('role:talent')->group(function () {
        Route::get('/talent', [TalentController::class, 'index'])->name('talent.index');
        Route::put('/talent', [TalentController::class, 'update'])->name('talent.update');
        Route::delete('/talent', [TalentController::class, 'destroy'])->name('talent.destroy'); 

    });
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

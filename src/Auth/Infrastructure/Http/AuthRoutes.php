<?php

use Illuminate\Support\Facades\Route;
use TeacherAi\Auth\Infrastructure\Http\Controllers\AuthController;


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

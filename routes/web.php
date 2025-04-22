<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GuestController;
use Illuminate\Support\Facades\Route;

Route::get('/register', [AuthController::class, 'renderRegistration'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/', [GuestController::class, 'index'])->name('guest.index');

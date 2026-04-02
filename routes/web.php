<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/usuarios', 'usuarios.index')->name('usuarios.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

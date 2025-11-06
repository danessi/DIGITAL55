<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Ruta para mostrar el formulario de login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

// Ruta para procesar el login (POST)
Route::post('/login', [AuthController::class, 'loginWeb']);

Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register'); // Página de registro
Route::post('/register', [AuthController::class, 'registerWeb']); // Registro web

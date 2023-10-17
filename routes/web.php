<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/about', [\App\Http\Controllers\AboutController::class, 'index'])->name('about');
Route::get('/about/storeComment', [\App\Http\Controllers\AboutController::class, 'storeComment'])->name('about.store');
Route::get('/menu', [\App\Http\Controllers\MenuController::class, 'index'])->name('menu');
Route::get('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/login/authenticate', [\App\Http\Controllers\AuthController::class, 'authenticate'])->name('authenticate');
Route::get('/registration', [\App\Http\Controllers\AuthController::class, 'registration'])->name('registration');
Route::post('/registration/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::post('/registration/checkEmail', [\App\Http\Controllers\AuthController::class, 'checkEmail'])->name('register.checkEmail');
Route::get('/registration/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::get('/maps', [\App\Http\Controllers\AuthController::class, 'index'])->name('maps');

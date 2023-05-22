<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PanitiaController;
use App\Http\Controllers\PesertaController;
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

// Login Routes
Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('/login', [AuthController::class, 'login']);

//Logout
Route::get('/logout', [AuthController::class, 'logout']);

//Register Routes
Route::get('/register', function(){
    return view('register');
});
Route::post('/register', [AuthController::class, 'register']);
Route::get('/otp', function(){
    return view('otp');
});

// Forgot Password Route
Route::get('/forget-password', function(){
    return view('forgetPassword');
});

// Admin Routes
Route::group(['middleware' => ['auth', 'level:admin']], function(){
    Route::get('/admin', [AdminController::class, 'index']);
    // semua route admin dibuat dalam route group ini!!
});

// Panitia Routes
Route::group(['middleware' => ['auth', 'level:peserta']], function(){
    Route::get('/panitia', [PanitiaController::class, 'index']);
    // semua route panitia dibuat dalam route group ini!!
});

// Peserta Routes
Route::group(['middleware' => ['auth', 'level:peserta']], function(){
    Route::get('/peserta', [PesertaController::class, 'index']);
    // semua route peserta dibuat dalam route group ini!!
});

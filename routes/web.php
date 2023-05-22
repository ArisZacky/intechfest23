<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PanitiaController;
use App\Http\Controllers\PesertaController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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

Route::get('/email/verify', [AuthController::class, 'emailNotice'])->middleware('auth')->name('verification.notice');

// route yang mengarahkan tombol verifikasi pada email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/admin');
})->middleware(['auth', 'signed','level:admin'])->name('verification.verify');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/panitia');
})->middleware(['auth', 'signed','level:panitia'])->name('verification.verify');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/peserta');
})->middleware(['auth', 'signed','level:peserta'])->name('verification.verify');

//resend email verifikasi
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Forgot Password Route
Route::get('/forget-password', function(){
    return view('forgetPassword');
});

// Admin Routes
Route::group(['middleware' => ['auth','verified','level:admin']], function(){
    Route::get('/admin', [AdminController::class, 'index']);
    // semua route admin dibuat dalam route group ini!!
});

// Panitia Routes
Route::group(['middleware' => ['auth','verified','level:panitia']], function(){
    Route::get('/panitia', [PanitiaController::class, 'index']);
    // semua route panitia dibuat dalam route group ini!!
});

// Peserta Routes
Route::group(['middleware' => ['auth','verified','level:peserta']], function(){
    Route::get('/peserta', [PesertaController::class, 'index']);
    // semua route peserta dibuat dalam route group ini!!
});

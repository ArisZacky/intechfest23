<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\AdminController;
use Illuminate\Auth\Events\PasswordReset;
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
Route::get('/forgot-password', function(){
    return view('auth.forgot-password');
});

// form forgot password
Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

// mengirim token ke email
Route::get('/reset-password/{token}', function (string $token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

// form reset password
Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);
 
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));
 
            $user->save();
 
            event(new PasswordReset($user));
        }
    );
 
    return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', __($status))
                : back()->withErrors(['email' => [__($status)]]);
   
})->middleware('guest')->name('password.update');

// Admin Routes
Route::group(['middleware' => ['auth','verified','level:admin']], function(){
    Route::get('/admin', [AdminController::class, 'index']);
    // semua route admin dibuat dalam route group ini!!

    // Menampilkan Halaman Childtalks
    Route::get('/childtalk-admin', [AdminController::class, 'ct']);
    // Menampilkan Halaman WDC
    Route::get('/wdc-admin', [AdminController::class, 'wdc']);
    // Menampilkan Halaman DC
    Route::get('/dc-admin', [AdminController::class, 'dc']);
    // Menmapilkan Halaman CTF
    Route::get('/ctf-admin', [AdminController::class, 'ctf']);
    // Menampilkan Halaman Transaksi
    Route::get('/transaksi-admin', [AdminController::class, 'transaksi']);
    // Menampilkan Halaman Akun Admin
    Route::get('/akun-panitia', [AdminController::class, 'panitia']);
    // Menampilkan Halaman Akun Peserta
    Route::get('/akun-peserta', [AdminController::class, 'peserta']);

});

// Panitia Routes
Route::group(['middleware' => ['auth','verified','level:panitia']], function(){
    Route::get('/panitia', [PanitiaController::class, 'index']);
    // semua route panitia dibuat dalam route group ini!!

    // Menampilkan Halaman Childtalks
    Route::get('/childtalk-panitia', [PanitiaController::class, 'ct']);

    // Menampilkan Halaman WDC
    Route::get('/wdc-panitia', [PanitiaController::class, 'wdc']);

    // Menampilkna Halaman DC
    Route::get('/dc-panitia', [PanitiaController::class, 'dc']);

    // Menampilkan Halaman CTF
    Route::get('/ctf-panitia', [PanitiaController::class, 'ctf']);

    // Menampilkan Halaman Transaksi
    Route::get('/transaksi-panitia', [PanitiaController::class, 'transaksi']);
});

// Peserta Routes
Route::group(['middleware' => ['auth','verified','level:peserta']], function(){
    Route::get('/peserta', [PesertaController::class, 'index']);
    // semua route peserta dibuat dalam route group ini!!
});

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
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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
Route::get('/register', function () {
    return view('register');
});

Route::post('/register', [AuthController::class, 'register']);

Route::get('/email/verify', [AuthController::class, 'emailNotice']);

// route yang mengarahkan tombol verifikasi pada email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/admin');
})->middleware(['auth', 'signed', 'level:admin'])->name('verification.verify');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/panitia');
})->middleware(['auth', 'signed', 'level:panitia'])->name('verification.verify');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/peserta');
})->middleware(['auth', 'signed', 'level:peserta'])->name('verification.verify');

//resend email verifikasi
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Forgot Password Route
Route::get('/forgot-password', function () {
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
Route::group(['middleware' => ['auth', 'verified', 'level:admin']], function () {
    Route::get('/admin', [AdminController::class, 'index']);
    // semua route admin dibuat dalam route group ini!!

    // Menampilkan Halaman Chilltalks
    Route::get('/chilltalk-admin', [AdminController::class, 'ct']);
    // Menampilkan Halaman Akun Chilltalks Yang terdelete
    Route::get('/deleted-data-chilltalks', [AdminController::class, 'getDeletedChilltalks']);
    // Mengembalikan data akun Chilltalks (restore)
    Route::get('/data-chilltalks/{$id}/restore', [AdminController::class, 'restoreChilltalks']);
    // Update Akun Chilltalks
    Route::post('/update-chilltalks', [AdminController::class, 'updateChilltalks']);
    // Delete Akun Chilltalks
    Route::post('/delete-chilltalks', [AdminController::class, 'deleteChilltalks']); 

    // Menampilkan Halaman WDC
    Route::get('/wdc-admin', [AdminController::class, 'wdc']);
    // Menampilkan Halaman Akun WDC Yang terdelete
    Route::get('/deleted-data-wdc', [AdminController::class, 'getDeletedWdc']);
    // Mengembalikan data akun WDC (restore)
    Route::get('/data-wdc/{$id}/restore', [AdminController::class, 'restoreWdc']);
    // Update Akun WDC
    Route::post('/update-wdc', [AdminController::class, 'updateWdc']);
    // Delete Akun WDC
    Route::post('/delete-wdc', [AdminController::class, 'deleteWdc']); 

    // Menampilkan Halaman DC
    Route::get('/dc-admin', [AdminController::class, 'dc']);
    // Menampilkan Halaman Akun DC Yang terdelete
    Route::get('/deleted-data-dc', [AdminController::class, 'getDeletedDc']);
    // Mengembalikan data akun DC (restore)
    Route::get('/data-dc/{$id}/restore', [AdminController::class, 'restoreDc']);
    // Update Akun DC
    Route::post('/update-dc', [AdminController::class, 'updateDc']);
    // Delete Akun DC
    Route::post('/delete-dc', [AdminController::class, 'deleteDc']); 

    // Menmapilkan Halaman CTF
    Route::get('/ctf-admin', [AdminController::class, 'ctf']);
    // Menampilkan Halaman Akun CTF Yang terdelete
    Route::get('/deleted-data-ctf', [AdminController::class, 'getDeletedCtf']);
    // Mengembalikan data akun CTF (restore)
    Route::get('/data-ctf/{$id}/restore', [AdminController::class, 'restoreCtf']);
    // Update Akun CTF
    Route::post('/update-ctf', [AdminController::class, 'updateCtf']);
    // Delete Akun CTF
    Route::post('/delete-ctf', [AdminController::class, 'deleteCtf']); 

    // Menampilkan Halaman Transaksi
    Route::get('/transaksi-admin', [AdminController::class, 'transaksi']);
    // Menampilkan Halaman Akun Transaksi Yang terdelete
    Route::get('/deleted-data-transaksi', [AdminController::class, 'getDeletedTransaksi']);
    // Mengembalikan data akun Transaksi (restore)
    Route::get('/data-transaksi/{$id}/restore', [AdminController::class, 'restoreTransaksi']);
    // Update Akun Transaksi
    Route::post('/update-transaksi', [AdminController::class, 'updateTransaksi']);
    // Delete Akun Transaksi
    Route::post('/delete-transaksi', [AdminController::class, 'deleteTransaksi']);

    // Menampilkan Halaman Akun Panitia
    Route::get('/data-panitia', [AdminController::class, 'panitia']);
    // Menampilkan Halaman Akun Panitia Yang terdelete
    Route::get('/deleted-data-panitia', [AdminController::class, 'getDeletedPanitia']);
    // Mengembalikan data akun Panitia (restore)
    Route::get('/data-panitia/{$id}/restore', [AdminController::class, 'restorePanitia']);
    // Update Akun Panitia
    Route::post('/update-panitia', [AdminController::class, 'updatePanitia']);
    // Delete Akun Panitia
    Route::post('/delete-panitia', [AdminController::class, 'deletePanitia']); 

    // Menampilkan Halaman Akun Peserta
    Route::get('/data-peserta', [AdminController::class, 'peserta']);
    // Menampilkan Halaman Project 
    Route::get('/project-admin', [AdminController::class, 'project']);

    // Menampilkan Halaman Akun Peserta Yang terdelete
    Route::get('/deleted-data-peserta', [AdminController::class, 'getDeletedPeserta']);
    // Mengembalikan data akun peserta (restore)
    Route::get('/data-peserta/{$id}/restore', [AdminController::class, 'restorePeserta']);
    // Update Akun Peserta
    Route::post('/update-peserta', [AdminController::class, 'updatePeserta']);
    // Delete Akun Peserta
    Route::post('/delete-peserta', [AdminController::class, 'deletePeserta']); 

});

// Panitia Routes
Route::group(['middleware' => ['auth', 'verified', 'level:panitia']], function () {
    Route::get('/panitia', [PanitiaController::class, 'index']);
    // semua route panitia dibuat dalam route group ini!!

// CT ================================================================================================================
    // Menampilkan Halaman Childtalks
    Route::get('/chilltalk-panitia', [PanitiaController::class, 'ct']);
     // delete ct
     Route::get('/ct-panit-delete/{id_ct}', [PanitiaController::class, 'delete_ct']);
     // menampilkan daftar hapus ct
     Route::get('/daftar-ct-hapus', [PanitiaController::class, 'ct_hapus']);
     // kembalikan data kehapus (restore)
     Route::get('/ct/{id}/restore', [PanitiaController::class, 'ct_kembali']);


// WDC ===============================================================================================================
    // Menampilkan Halaman WDC
    Route::get('/wdc-panitia', [PanitiaController::class, 'wdc']);
    // delete wdc
    Route::get('/wdc-panit-delete/{id_wdc}', [PanitiaController::class, 'delete_wdc']);
    // menampilkan daftar hapus wdc
    Route::get('/daftar-wdc-hapus', [PanitiaController::class, 'wdc_hapus']);
    // kembalikan data kehapus (restore)
    Route::get('/wdc/{id}/restore', [PanitiaController::class, 'wdc_kembali']);

// DC ================================================================================================================
    // Menampilkna Halaman DC
    Route::get('/dc-panitia', [PanitiaController::class, 'dc']);
     // delete dc
     Route::get('/dc-panit-delete/{id_dc}', [PanitiaController::class, 'delete_dc']);
     // menampilkan daftar hapus dc
     Route::get('/daftar-dc-hapus', [PanitiaController::class, 'dc_hapus']);
     // kembalikan data kehapus (restore)
     Route::get('/dc/{id}/restore', [PanitiaController::class, 'dc_kembali']);

// CTF ===============================================================================================================
    // Menampilkan Halaman CTF
    Route::get('/ctf-panitia', [PanitiaController::class, 'ctf']);
     // delete ctf
     Route::get('/ctf-panit-delete/{id_ctf}', [PanitiaController::class, 'delete_ctf']);
     // menampilkan daftar hapus ctf
     Route::get('/daftar-ctf-hapus', [PanitiaController::class, 'ctf_hapus']);
     // kembalikan data kehapus (restore)
     Route::get('/ctf/{id}/restore', [PanitiaController::class, 'ctf_kembali']);

// TRANSAKSI =========================================================================================================
    // Menampilkan Halaman Transaksi
    Route::get('/transaksi-panitia', [PanitiaController::class, 'transaksi']);
    // delete dc
    Route::get('/transaksi-panit-delete/{id_transaksi}', [PanitiaController::class, 'delete_trans']);
    // menampilkan daftar hapus dc
    Route::get('/daftar-transaksi-hapus', [PanitiaController::class, 'trans_hapus']);
    // kembalikan data kehapus (restore)
    Route::get('/transaksi/{id}/restore', [PanitiaController::class, 'trans_kembali']);

// PROJECT ===========================================================================================================
    // Menampilkan Halaman Project
    Route::get('/project-panitia', [PanitiaController::class, 'project']);
     // delete dc
     Route::get('/project-panit-delete/{id_project}', [PanitiaController::class, 'delete_project']);
     // menampilkan daftar hapus dc
     Route::get('/daftar-project-hapus', [PanitiaController::class, 'project_hapus']);
     // kembalikan data kehapus (restore)
     Route::get('/project/{id}/restore', [PanitiaController::class, 'project_kembali']);
});

// Peserta Routes
Route::group(['middleware' => ['auth', 'verified', 'level:peserta']], function () {
    Route::get('/peserta', [PesertaController::class, 'index']);
    // semua route peserta dibuat dalam route group ini!!
        // Menampilkan Halaman Chilltalks
        Route::get('/chilltalk-peserta', [PesertaController::class, 'ct']);
        // Menampilkan Halaman DC
        Route::get('/dc-peserta', [PesertaController::class, 'dc']);
        // Menampilkan Halaman WDC
        Route::get('/wdc-peserta', [PesertaController::class, 'wdc']);
        // Menampilkan Halaman CTF
        Route::get('/ctf-peserta', [PesertaController::class, 'ctf']);
        // Menampilkan Halaman Transaksi
        Route::get('/transaksi-peserta', [PesertaController::class, 'transaksi']);
    });

Route::get('tampilAdmin', function(){
    return view('admin');
});
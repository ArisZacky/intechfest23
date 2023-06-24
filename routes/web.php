<?php

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LombaController;
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

// landing page
Route::get('/', function(){
    return view('landing.content.home');
});
Route::get('/detail-dc', function(){
    return view('landing.content.dc');
});

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
    // Export Excel Chilltalks
    Route::get('/chilltalk-admin/export_excel', [AdminController::class, 'ctExportExcel']);
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
    // Menampilkan Halaman WDC
    Route::get('/wdc-admin/downloadWdc/{file_name}', [AdminController::class, 'downloadWdc']);
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
    // Menampilkan Halaman Chilltalks
    Route::get('/chilltalk-panitia', [PanitiaController::class, 'ct']);
     // delete ct
     Route::post('/ct-delete', [PanitiaController::class, 'delete_ct']);

// WDC ===============================================================================================================
    // Menampilkan Halaman WDC
    Route::get('/wdc-panitia', [PanitiaController::class, 'wdc']);
    // delete wdc
    Route::post('/wdc-delete', [PanitiaController::class, 'delete_wdc']);
    // update wdc
    Route::post('/wdc-update', [PanitiaController::class, 'update_wdc']);

// DC ================================================================================================================
    // Menampilkna Halaman DC
    Route::get('/dc-panitia', [PanitiaController::class, 'dc']);
     // delete dc
    Route::post('/dc-delete', [PanitiaController::class, 'delete_dc']);
    // update dc
    Route::post('/dc-update', [PanitiaController::class, 'update_dc']);

// CTF ===============================================================================================================
    // Menampilkan Halaman CTF
    Route::get('/ctf-panitia', [PanitiaController::class, 'ctf']);
     // delete ctf
     Route::post('/ctf-delete', [PanitiaController::class, 'delete_ctf']);
    // update ctf
     Route::post('/ctf-update', [PanitiaController::class, 'update_ctf']);

// TRANSAKSI =========================================================================================================
    // Menampilkan Halaman Transaksi
    Route::get('/transaksi-panitia', [PanitiaController::class, 'transaksi']);
    // delete Transaksi
    Route::post('/transaksi-delete', [PanitiaController::class, 'delete_transaksi']);
    // update transaksi
    Route::post('/transaksi-update', [PanitiaController::class, 'update_transaksi']);

// PROJECT ===========================================================================================================
    // Menampilkan Halaman Project
    Route::get('/project-panitia', [PanitiaController::class, 'project']);
     // delete project
     Route::post('project-delete', [PanitiaController::class, 'delete_project']);

});

// Peserta Routes
Route::group(['middleware' => ['auth', 'verified', 'level:peserta']], function () {
    Route::get('/peserta', [PesertaController::class, 'index']);
    // semua route peserta dibuat dalam route group ini!!
        // Menampilkan Halaman profoil
        Route::get('/profil-peserta', [PesertaController::class, 'profil']);
        // Edit Profile Peserta
        Route::put('/profile-peserta/{id}', [PesertaController::class, 'edit_profile']);
        // Menampilkan Halaman lomba
        Route::get('/lomba-peserta', [PesertaController::class, 'lomba']);
        // Menampilkan Halaman lomba
        Route::get('/chilltalks-peserta', [PesertaController::class, 'chilltalks']);
    });

Route::get('tampilAdmin', function(){
    return view('admin');
});

//Route Daftar cabang lomba
Route::group(['middleware' => ['auth', 'verified', 'level:peserta']], function (){
    // ========================================================================== WDC
    // Menampilkan Form Daftar Lomba WDC
    Route::get('/wdc', [LombaController::class, 'wdc']);
    // Route yang mengarahkan proses daftar wdc
    Route::put('/daftar-wdc/{id}', [LombaController::class, 'daftarwdc']);
    // Menampilkan dashboard peserta wdc
    Route::get('/peserta-wdc', [LombaController::class, 'dashboardwdc']);

    // Menampilkan Transaksi
    Route::get('/pembayaran', [LombaController::class, 'pembayaranwdc']);

    // ============================================================================== DC
    // Menampilkan Form Daftar Lomba WDC
    Route::get('/dc', [LombaController::class, 'dc']);
    // Route yang mengarahkan proses daftar wdc
    Route::put('/daftar-dc/{id}', [LombaController::class, 'daftardc']);
    // Menampilkan dashboard peserta wdc
    Route::get('/peserta-dc', [LombaController::class, 'dashboarddc']);

    // Menampilkan Transaksi
    Route::get('/pembayaran', [LombaController::class, 'pembayarandc']);

     // ============================================================================== CTF
    // Menampilkan Form Daftar Lomba WDC
    Route::get('/ctf', [LombaController::class, 'ctf']);
    // Route yang mengarahkan proses daftar wdc
    Route::put('/daftar-ctf/{id}', [LombaController::class, 'daftarctf']);
    // Menampilkan dashboard peserta wdc
    Route::get('/peserta-ctf', [LombaController::class, 'dashboardctf']);

    // Menampilkan Transaksi
    Route::get('/pembayaran', [LombaController::class, 'pembayaranctf']);
});
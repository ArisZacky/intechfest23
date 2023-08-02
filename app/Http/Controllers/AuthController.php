<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Panitia;
use App\Models\Peserta;
use App\Mail\EmailSender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
        
        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();
            
            $user_data = User::where('email', $request->email)->first();
            $level = $user_data->level;
            
            //membawa ke halaman sesuai level
            if($level == "admin")
                return redirect('/admin');
            elseif($level == "panitia"){
                return redirect('/panitia');
            }else{
                return redirect('/peserta');
            }
        }else{
            return redirect('login')->with('gagal', 'Email Atau Password Salah !');
        }
    }

    public function register(Request $request)
    {
        // menangkap data user dari `inputan from
        $password = bcrypt($request->password);
        $data = [
            'name' => $request->nama, 
            'email'=>$request->email, 
            'password' => $password, 
            'level' => 'peserta' // level diganti ke panitia kalo mau pakek daftar panitia
        ];

        $validated = $request->validate([
            'email' => 'unique:users',
        ]);

        // ====================================PESERTA====================================
        // Database Transaction untuk insert data ke 2 table
        try {
            DB::beginTransaction();
            
            // Insert data ke table users menggunakan variavel data
            $user = User::create($data);
    
            // Insert data ke table peserta 
            Peserta::create([ 
                'email' => $request->email, 
                'nama_lengkap' => $request->nama
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
        // ====================================END PESERTA====================================

        // ====================================PANITIA========================================
        // try {
        //     DB::beginTransaction();
            
        //     // Insert data ke table users menggunakan variavel data
        //     $user = User::create($data);
    
        //     // Insert data ke table peserta 
        //     Panitia::create([ 
        //         'email_panitia' => $request->email, 
        //         'nama_lengkap' => $request->nama
        //     ]);

        //     DB::commit();
        // } catch (\Throwable $th) {
        //     throw $th;
        //     DB::rollBack();
        // }
        // ====================================END PANITIA====================================

        //membuat user login tetapi akun belum terverifikasi
        event(new Registered($user));
        Auth::login($user);

        return redirect('/email/verify');
        
    }

    // halaman verifikasi email
    public function emailNotice(){
        return view('auth.verify-email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/login');
    }

}
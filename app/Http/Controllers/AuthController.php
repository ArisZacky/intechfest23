<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\EmailSender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
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
            return redirect('login');
        }
    }

    public function register(Request $request)
    {
        // menangkap data user dari inputan from
        $data = [
            'name' => $request->nama, 
            'email'=>$request->email, 
            'password' => $request->password, 
            'level' => 'peserta'
        ];

        // menambahkan data user ke db melaui variabel data
        $user = User::create($data);

        //membuat user login tetapi akun belum terverifikasi
        event(new Registered($user));
        Auth::login($user);

        return redirect('/email/verify');

        // Code OTP
        // $otp1 = rand(0,9);
        // $otp2 = rand(0,9);
        // $otp3 = rand(0,9);
        // $otp4 = rand(0,9);
        // $otp = ['otp' => "$otp1"."$otp2"."$otp3"."$otp4"];
        // Mail::send('emails.otpmail', $otp, function($mail) use ($request) {
        //     $mail->to($request->email)->subject('OTP');
        // });
        // // return user
        // if($user){
        //     return redirect('/login');
        // } else {
        //     return redirect('/register');
        // }
        
    }

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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

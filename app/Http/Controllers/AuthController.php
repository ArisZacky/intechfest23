<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailSender;

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
            return redirect()->intended('/register');
        }else{
            return redirect('login');
        }
    }

    public function register(Request $request)
    {
        // create user status unverified
        $data = [
            'name' => $request->nama, 
            'email'=>$request->email, 
            'password' => $request->password, 
            'level' => 'peserta'
        ];

        $user = User::create($data);
        // send email
        Mail::to($request->email)->send(new EmailSender());
        // return user
        if($user){
            return redirect('/login');
        } else {
            return redirect('/register');
        }
        
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

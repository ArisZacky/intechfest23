<?php

namespace App\Http\Controllers;

use App\Models\Ct;
use App\Models\Dc;
use App\Models\Ctf;
use App\Models\Wdc;
use App\Models\User;
use App\Models\Peserta;
use App\Models\Project;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Compilers\Concerns\CompilesRawPhp;

class PesertaController extends Controller
{
    public function index(){
        Auth::user();
        return view('peserta.content.dashboard');
    }

    public function profil(){
        $user = Auth::user();
        $peserta = Peserta::where('email', $user->email)->first();
        return view('peserta.content.profil', ['peserta' => $peserta]);
    }

    public function lomba(){
        return view('peserta.content.lomba');
    }

    public function chilltalks(){
        return view('peserta.content.chilltalks');
    }

    public function edit_profile(Request $request, $id){
        $peserta = Peserta::findOrFail($id);
        
        $users = [
            'name' => $request->nama_lengkap
        ];

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'alamat' => $request->alamat,
            'nama_instansi' => $request->nama_instansi,
            'no_hp' => $request->no_hp,
        ];

        try {
            DB::beginTransaction();
            
            // update data ke table peserta 
            $peserta->update($data);

            // update data ke table users yang memiliki email yang sama pada form
            User::where('email', $request->email)->update($users);

            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }

        return redirect('/profil-peserta')->with('berhasil', 'Data Berhasil Disimpan !');
    }

}

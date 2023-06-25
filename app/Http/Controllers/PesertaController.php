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
    public function index()
    {
        Auth::user();
        return view('peserta.content.dashboard');
    }

    public function profil()
    {
        $user = Auth::user();
        $peserta = Peserta::where('email', $user->email)->first();
        return view('peserta.content.profil', ['peserta' => $peserta]);
    }

    public function lomba()
    {
        $user = Auth::user();
        $id_peserta = Peserta::where('email', $user->email)->first()->id_peserta;

        $peserta = null;
        // cek jika peserta yang login mendaftar salah 1 dari 3 lomba yang ada
        $wdcPeserta = Wdc::where('id_peserta', $id_peserta)->first();
        if ($wdcPeserta) {
            $lomba = "WDC";
            $peserta = $wdcPeserta;
        }
        $dcPeserta = Dc::where('id_peserta', $id_peserta)->first();
        if ($dcPeserta) {
            $lomba = "DC";
            $peserta = $dcPeserta;
        }
        $ctfPeserta = Ctf::where('id_peserta', $id_peserta)->first();
        if ($ctfPeserta) {
            $lomba = "CTF";
            $peserta = $ctfPeserta;
        }

        // jika belum mendaftar lomba apapun
        if (empty($peserta)){
            return view('peserta.content.lomba');
        } // jika sudah mendaftar tapi belum divalidasi (step 2)
        else if($peserta->validasi == "Belum Tervalidasi"){
            return view('peserta.lomba.validasi_formulir', compact('peserta'));
        } // jika sudah divalidasi tapi belum melakukan pembayaran untuk lomba dc (step 3)
        else if($peserta->validasi == "Sudah Valid" AND empty($peserta->id_transaksi) AND $lomba == "DC") {
            return view('peserta.lomba.transaksiDc', compact('peserta'));
        } // jika sudah divalidasi tapi belum melakukan pembayaran untuk lomba wdc (step 3)
        else if($peserta->validasi == "Sudah Valid" AND empty($peserta->id_transaksi) AND $lomba == "WDC") {
            return view('peserta.lomba.transaksiWdc', compact('peserta'));
        } // jika sudah divalidasi tapi belum melakukan pembayaran untuk lomba wdc (step 3)
        else if($peserta->validasi == "Sudah Valid" AND empty($peserta->id_transaksi) AND $lomba == "CTF") {
            return view('peserta.lomba.transaksiCtf', compact('peserta'));
        }
    }

    public function chilltalks()
    {
        return view('peserta.content.chilltalks');
    }

    public function edit_profile(Request $request, $id)
    {
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
            DB::rollBack();
            throw $th;
        }

        return redirect('/profil-peserta')->with('berhasil', 'Data Berhasil Disimpan !');
    }

}
<?php

namespace App\Http\Controllers;

use App\Models\Wdc;
use App\Models\User;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Storage;

class LombaController extends Controller
{
    public function wdc(){
        //mengambil data dari user yang login pada table users
        $user = Auth::user();
        //mengambil data dari table peserta yang memiliki email sama dengan user yang login 
        //pada table users dengan mencocokan email pada table peserta dengan table users
        $data_peserta = Peserta::where('email', $user->email)->first();
        //return view lomba dan data dalam bentuk object
        return view('lomba.wdc', ['user'=> $user, 'data_peserta'=> $data_peserta]);
    }

    public function daftarwdc(Request $request, $id){
        
        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'alamat' => 'required',
            'nama_instansi' => 'required',
            'no_hp' => 'required|numeric',
            'foto' => 'required|mimes:png,jpg,jpeg|max:2048'
        ]);

        // mencari id yang sama pada table peserta yang dikirim kan melalui url
        $tb_peserta = Peserta::findOrFail($id);
        $tb_wdc = Wdc::get();

        $nama_lomba = "WDC";

        // ===============Membuat Nomer Peserta=======================
            $currentCount = Wdc::count() + 1; // Menghitung jumlah peserta yang sudah ada dan menambahkannya dengan 1
            
            // Set timezone
            date_default_timezone_set('Asia/Singapore');

            // membuat custom angka
            $currentTimestamp   = now()->format('dHis'); // Mengambil tanggal, jam, menit, dan detik saat ini
            $currentDay         = substr($currentTimestamp, 0, 2); // Mengambil 2 angka tanggal
            $currentHour        = substr($currentTimestamp, 2, 2); // Mengambil 2 angka jam
            $currentSecond      = substr($currentTimestamp, -2); // Mengambil 2 angka detik terakhir

            $nomer_peserta      = '1'.str_pad($currentCount, 3, '0', STR_PAD_LEFT).$currentDay.$currentHour.$currentSecond;
        // ==============Nomer Peserta End=============================
        
        //================================Upload Foto====================
            $foto       = $request->foto; 
            $filename   = "WDC_".$request->nama_lengkap.'_'.$foto->getClientOriginalName(); // format nama file
            $path       = 'Identitas/wdc/'.$filename; // tempat penyimpanan file

            Storage::disk('public')->put($path,file_get_contents($foto));
         //============================End Upload Foto====================

        // data yang akan di update ke table peserta
        $peserta = [
            'nomer_peserta' => $nomer_peserta,
            'nama_lengkap' => $request->nama_lengkap,
            'alamat' => $request->alamat,
            'nama_instansi' => $request->nama_instansi,
            'no_hp' => $request->no_hp
        ];

        // data yang akan di update ke table users
        $users = [
            'name' => $request->nama_lengkap
        ];
        
        // data yang akan di insert ke table wdc
        $wdc = [
            'id_peserta' => $request->id_peserta,
            'foto' => $filename,
            'validasi' => 'Belum Tervalidasi'
        ];
        
        // Database Transaction untuk insert data ke 3 table
        try {
            DB::beginTransaction();
            
            // update data ke table peserta 
            $tb_peserta->update($peserta);

            // update data ke table users yang memiliki email yang sama pada form
            User::where('email', $request->email)->update($users);

            // insert data ke table wdc
            Wdc::create($wdc);
            
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
        return redirect('/peserta-wdc');
    }

    public function dashboardwdc(){
        $data_peserta = Wdc::with('peserta')->get();
        return view('peserta.content.wdc', ['data_peserta' => $data_peserta]);
    }

    public function pembayaran(){
        return view('peserta.content.pembayaran-lomba');
    }
}

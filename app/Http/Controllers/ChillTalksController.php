<?php

namespace App\Http\Controllers;

use App\Models\Ct;
use App\Models\User;
use App\Models\Peserta;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Storage;

class ChillTalksController extends Controller
{

    public function daftarCt(Request $request, $id)
    {

        $request->validate([
            'nama_lengkap' => 'required',
            'alamat' => 'required',
            'nama_instansi' => 'required',
            'no_hp' => 'required|numeric',
            'foto' => 'required|mimes:png,jpg,jpeg|max:2000'
        ]);

        // mencari id yang sama pada table peserta yang dikirim kan melalui url
        $tb_peserta = Peserta::findOrFail($id);

        // ===============Membuat Nomer Peserta=======================
        // menghitung jumlah peserta yang ada di table ct lalu tambah 1
        $jumlahPeserta = Ct::count() + 1;
        // jumlah maksimal peserta yang bisa mendaftar
        $maksimalPeserta = 1000;
        if ($jumlahPeserta <= $maksimalPeserta) {
            // format nomer peserta menjadi 4 digit
            $nomer_peserta = str_pad($jumlahPeserta, 4, '0', STR_PAD_LEFT);
        } else {
            return redirect()->back()->with('error', 'Maaf, Kuota Peserta Sudah Penuh');
        }

        //================================Upload Foto Transaksi====================
        $filePath = $this->uploadTransaksiCtf($request, $request->nama_lengkap);
        //============================End Upload Foto====================

        // data yang akan di update ke table peserta
        $peserta = [
            'nama_lengkap' => $request->nama_lengkap,
            'alamat' => $request->alamat,
            'nama_instansi' => $request->nama_instansi,
            'no_hp' => $request->no_hp
        ];

        // data yang akan di update ke table users
        $users = [
            'name' => $request->nama_lengkap
        ];

        // data yang akan di insert ke table transaski
        $transaksi = [
            'foto' => $filePath,
            'validasi' => 'Belum Tervalidasi'
        ];

        // data yang akan di insert ke table ct
        $ct = [
            'nomer_peserta' => $nomer_peserta,
            'id_peserta' => $request->id_peserta
        ];
        

        // Database Transaction untuk insert data ke 3 table
        try {
            DB::beginTransaction();

            $idTransaksi = DB::table('transaksi')->insertGetId($transaksi);

            // update data ke table peserta 
            $tb_peserta->update($peserta);

            // update data ke table users yang memiliki email yang sama pada form
            User::where('email', $request->email)->update($users);

            // insert data ke table dc
            $ct['id_transaksi'] = $idTransaksi;
            Ct::create($ct);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        return redirect('/chilltalks-peserta');
    }

    public function uploadTransaksiCtf(Request $request, $namaPeserta)
    {
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            // format nama dan path foto sesuaiin dengan cabang lomba
            $filename = "CT_Bukti Transfer_" . $namaPeserta . '_' .time(). '.' . $foto->getClientOriginalExtension();
            $path = 'transfer/' . $filename;
            // simpan foto ke storage
            Storage::disk('public')->put($path, file_get_contents($foto));
            return $path;
        }
        // error message jika gagal upload foto
        return redirect()->back()->with('error', 'Gagal mengunggah foto.');
    }
}
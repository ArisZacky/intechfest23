<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Wdc;
use App\Models\Dc;
use App\Models\Ctf;
use App\Models\User;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Storage;

class LombaController extends Controller
{
    // =============================================================================== WDC
    public function wdc()
    {
        //mengambil data dari user yang login pada table users
        $user = Auth::user();
        //mengambil data dari table peserta yang memiliki email sama dengan user yang login 
        //pada table users dengan mencocokan email pada table peserta dengan table users
        $data_peserta = Peserta::where('email', $user->email)->first();
        //return view lomba dan data dalam bentuk object
        return view('lomba.wdc', ['user' => $user, 'data_peserta' => $data_peserta]);
    }

    public function daftarwdc(Request $request, $id)
    {

        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'alamat' => 'required',
            'nama_instansi' => 'required',
            'no_hp' => 'required|numeric',
            'foto' => 'required|mimes:png,jpg,jpeg|max:5000'
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
        $currentTimestamp = now()->format('dHis'); // Mengambil tanggal, jam, menit, dan detik saat ini
        $currentDay = substr($currentTimestamp, 0, 2); // Mengambil 2 angka tanggal
        $currentHour = substr($currentTimestamp, 2, 2); // Mengambil 2 angka jam
        $currentSecond = substr($currentTimestamp, -2); // Mengambil 2 angka detik terakhir

        $nomer_peserta = '1' . str_pad($currentCount, 3, '0', STR_PAD_LEFT) . $currentDay . $currentHour . $currentSecond;
        // ==============Nomer Peserta End=============================

        //================================Upload Foto====================
        $foto = $request->foto;
        $filename = "WDC_" . $request->nama_lengkap . '_' . $foto->getClientOriginalName(); // format nama file
        $path = 'Identitas/wdc/' . $filename; // tempat penyimpanan file

        Storage::disk('public')->put($path, file_get_contents($foto));
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

    public function transaksiWdc(Request $request, $id)
    {
        // cari data yang login (gk perlu dirubah)
        $user = Auth::user();
        $peserta = Peserta::where('email', $user->email)->first();
        $namaPeserta = $peserta->nama_lengkap;
        $idPeserta = $peserta->id_peserta;

        // validasi foto (gk perlu diubah)
        $request->validate([
            'foto' => 'required|mimes:png,jpg,jpeg|max:5000'
        ],[
            'foto.mimes' => 'Format foto harus berupa JPG, JPEG, atau PNG.',
        ]);

        // jalankan fungsi uploadTransaksi dan dapatkan path foto (sesuaikan dengan nama fungsi)
        $filePath = $this->uploadTransaksiWdc($request, $namaPeserta);
        
        try{
            DB::beginTransaction();
            // data yang akan di insert ke table transaksi
            $data = ['foto' => $filePath, 'validasi' => 'Belum Tervalidasi'];
            // insert data ke transaksi dan ambil idnya
            $idTransaksi = DB::table('transaksi')->insertGetId($data);
            // data yang akan di update ke table dc yaitu kolom id_transaksi aja
            $data2 = ['id_transaksi' => $idTransaksi];
            // update kolom id_transaksi pada table dc (sesuaiin dengan cabang lomba)
            DB::table('wdc')->where('id_peserta', $idPeserta)->update($data2); 
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect('/lomba-peserta');
    }

    public function dashboardwdc()
    {
        $data_peserta = Wdc::with('peserta')->get();
        return view('peserta.content.wdc', ['data_peserta' => $data_peserta]);
    }

    public function uploadTransaksiWdc(Request $request, $namaPeserta)
    {
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            // format nama dan path foto sesuaiin dengan cabang lomba
            $filename = "WDC_Bukti Transfer_" . $namaPeserta . '.' . $foto->getClientOriginalExtension();
            $path = 'transfer/wdc/' . $filename;
            // simpan foto ke storage
            Storage::disk('public')->put($path, file_get_contents($foto));
            return $path;
        }
        // error message jika gagal upload foto
        return redirect()->back()->with('error', 'Gagal mengunggah foto.');
    }


    // =========================================================================================== DC

    public function dc()
    {
        //mengambil data dari user yang login pada table users
        $user = Auth::user();
        //mengambil data dari table peserta yang memiliki email sama dengan user yang login 
        //pada table users dengan mencocokan email pada table peserta dengan table users
        $data_peserta = Peserta::where('email', $user->email)->first();
        //return view lomba dan data dalam bentuk object
        return view('peserta.lomba.form-dc', ['user' => $user, 'data_peserta' => $data_peserta]);
    }

    public function daftardc(Request $request, $id)
    {

        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'alamat' => 'required',
            'nama_instansi' => 'required',
            'no_hp' => 'required|numeric',
            'foto' => 'required|mimes:png,jpg,jpeg|max:5000'
        ]);

        // mencari id yang sama pada table peserta yang dikirim kan melalui url
        $tb_peserta = Peserta::findOrFail($id);
        $tb_dc = Dc::get();

        $nama_lomba = "DC";

        // ===============Membuat Nomer Peserta=======================
        $currentCount = Dc::count() + 1; // Menghitung jumlah peserta yang sudah ada dan menambahkannya dengan 1

        // Set timezone
        date_default_timezone_set('Asia/Singapore');

        // membuat custom angka
        $currentTimestamp = now()->format('dHis'); // Mengambil tanggal, jam, menit, dan detik saat ini
        $currentDay = substr($currentTimestamp, 0, 2); // Mengambil 2 angka tanggal
        $currentHour = substr($currentTimestamp, 2, 2); // Mengambil 2 angka jam
        $currentSecond = substr($currentTimestamp, -2); // Mengambil 2 angka detik terakhir

        $nomer_peserta = '2' . str_pad($currentCount, 3, '0', STR_PAD_LEFT) . $currentDay . $currentHour . $currentSecond;
        // ==============Nomer Peserta End=============================

        //================================Upload Foto====================
        $foto = $request->foto;
        $filename = "DC_" . $request->nama_lengkap . '_' . $foto->getClientOriginalName(); // format nama file
        $path = 'Identitas/dc/' . $filename; // tempat penyimpanan file

        Storage::disk('public')->put($path, file_get_contents($foto));
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

        // data yang akan di insert ke table dc
        $dc = [
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

            // insert data ke table dc
            Dc::create($dc);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        return redirect('/lomba-peserta');
    }


    public function dashboarddc()
    {
        $data_peserta = Dc::with('peserta')->get();
        return view('peserta.content.dc', ['data_peserta' => $data_peserta]);
    }

    public function transaksiDc(Request $request, $id)
    {
        // cari data yang login (gk perlu dirubah)
        $user = Auth::user();
        $peserta = Peserta::where('email', $user->email)->first();
        $namaPeserta = $peserta->nama_lengkap;
        $idPeserta = $peserta->id_peserta;

        // validasi foto (gk perlu diubah)
        $request->validate([
            'foto' => 'required|mimes:png,jpg,jpeg|max:5000'
        ],[
            'foto.mimes' => 'Format foto harus berupa JPG, JPEG, atau PNG.',
        ]);

        // jalankan fungsi uploadTransaksi dan dapatkan path foto (sesuaikan dengan nama fungsi)
        $filePath = $this->uploadTransaksiDc($request, $namaPeserta);
        
        try{
            DB::beginTransaction();
            // data yang akan di insert ke table transaksi
            $data = ['foto' => $filePath, 'validasi' => 'Belum Tervalidasi'];
            // insert data ke transaksi dan ambil idnya
            $idTransaksi = DB::table('transaksi')->insertGetId($data);
            // data yang akan di update ke table dc yaitu kolom id_transaksi aja
            $data2 = ['id_transaksi' => $idTransaksi];
            // update kolom id_transaksi pada table dc (sesuaiin dengan cabang lomba)
            DB::table('dc')->where('id_peserta', $idPeserta)->update($data2); 
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        return redirect('/lomba-peserta');
    }

    public function uploadTransaksiDc(Request $request, $namaPeserta)
    {
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            // format nama dan path foto sesuaiin dengan cabang lomba
            $filename = "DC_Bukti Transfer_" . $namaPeserta . '.' . $foto->getClientOriginalExtension();
            $path = 'transfer/dc/' . $filename;
            // simpan foto ke storage
            Storage::disk('public')->put($path, file_get_contents($foto));
            return $path;
        }
        // error message jika gagal upload foto
        return redirect()->back()->with('error', 'Gagal mengunggah foto.');
    }

    // ====================================================================================== CTF
    public function ctf()
    {
        //mengambil data dari user yang login pada table users
        $user = Auth::user();
        //mengambil data dari table peserta yang memiliki email sama dengan user yang login 
        //pada table users dengan mencocokan email pada table peserta dengan table users
        $data_peserta = Peserta::where('email', $user->email)->first();
        //return view lomba dan data dalam bentuk object
        return view('lomba.ctf', ['user' => $user, 'data_peserta' => $data_peserta]);
    }

    public function daftarctf(Request $request, $id)
    {

        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'alamat' => 'required',
            'nama_instansi' => 'required',
            'no_hp' => 'required|numeric',
            'anggota1' => 'required',
            'anggota2' => 'required',
            'foto' => 'required|mimes:pdf|max:5000'
        ]);

        // mencari id yang sama pada table peserta yang dikirim kan melalui url
        $tb_peserta = Peserta::findOrFail($id);
        $tb_ctf = Ctf::get();

        $nama_lomba = "CTF";

        // ===============Membuat Nomer Peserta=======================
        $currentCount = Ctf::count() + 1; // Menghitung jumlah peserta yang sudah ada dan menambahkannya dengan 1

        // Set timezone
        date_default_timezone_set('Asia/Singapore');

        // membuat custom angka
        $currentTimestamp = now()->format('dHis'); // Mengambil tanggal, jam, menit, dan detik saat ini
        $currentDay = substr($currentTimestamp, 0, 2); // Mengambil 2 angka tanggal
        $currentHour = substr($currentTimestamp, 2, 2); // Mengambil 2 angka jam
        $currentSecond = substr($currentTimestamp, -2); // Mengambil 2 angka detik terakhir

        $nomer_peserta = '3' . str_pad($currentCount, 3, '0', STR_PAD_LEFT) . $currentDay . $currentHour . $currentSecond;
        // ==============Nomer Peserta End=============================

        //================================Upload Foto====================
        $foto = $request->foto;
        $filename = "Ctf_" . $request->nama_lengkap . '_' . $foto->getClientOriginalName(); // format nama file
        $path = 'Identitas/ctf/' . $filename; // tempat penyimpanan file

        Storage::disk('public')->put($path, file_get_contents($foto));
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

        // data yang akan di insert ke table ctf
        $ctf = [
            'id_peserta' => $request->id_peserta,
            'nama_team' => $request->nama_team,
            'anggota1' => $request->anggota1,
            'anggota2' => $request->anggota2,
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
            Ctf::create($ctf);

            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            DB::rollBack();
        }
        return redirect('/peserta-ctf');
    }


    public function dashboardctf()
    {
        $data_peserta = Ctf::with('peserta')->get();
        return view('peserta.content.ctf', ['data_peserta' => $data_peserta]);
    }

    
    public function transaksiCtf(Request $request, $id)
    {
        // cari data yang login (gk perlu dirubah)
        $user = Auth::user();
        $peserta = Peserta::where('email', $user->email)->first();
        $namaPeserta = $peserta->nama_lengkap;
        $idPeserta = $peserta->id_peserta;
        
        // validasi foto (gk perlu diubah)
        $request->validate([
            'foto' => 'required|mimes:png,jpg,jpeg|max:5000'
        ],[
            'foto.mimes' => 'Format foto harus berupa JPG, JPEG, atau PNG.',
        ]);
        
        // jalankan fungsi uploadTransaksi dan dapatkan path foto (sesuaikan dengan nama fungsi)
        $filePath = $this->uploadTransaksiCtf($request, $namaPeserta);
        
        try{
            DB::beginTransaction();
            // data yang akan di insert ke table transaksi
            $data = ['foto' => $filePath, 'validasi' => 'Belum Tervalidasi'];
            // insert data ke transaksi dan ambil idnya
            $idTransaksi = DB::table('transaksi')->insertGetId($data);
            // data yang akan di update ke table dc yaitu kolom id_transaksi aja
            $data2 = ['id_transaksi' => $idTransaksi];
            // update kolom id_transaksi pada table dc (sesuaiin dengan cabang lomba)
            DB::table('ctf')->where('id_peserta', $idPeserta)->update($data2); 
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
        
        return redirect('/lomba-peserta');
    }
    
    public function uploadTransaksiCtf(Request $request, $namaPeserta)
    {
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            // format nama dan path foto sesuaiin dengan cabang lomba
            $filename = "CTF_Bukti Transfer_" . $namaPeserta . '.' . $foto->getClientOriginalExtension();
            $path = 'transfer/ctf/' . $filename;
            // simpan foto ke storage
            Storage::disk('public')->put($path, file_get_contents($foto));
            return $path;
        }
        // error message jika gagal upload foto
        return redirect()->back()->with('error', 'Gagal mengunggah foto.');
    }
}
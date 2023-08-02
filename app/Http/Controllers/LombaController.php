<?php

namespace App\Http\Controllers;

use App\Models\Dc;
use Carbon\Carbon;
use App\Models\Ctf;
use App\Models\Wdc;
use App\Models\User;
use App\Models\Peserta;
use App\Models\Transaksi;
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
        // atur batas
        $batasWaktuWDC = new Carbon('2023-08-16 23:59:59');
        if($batasWaktuWDC->isPast()){
            return view('errors.waktuHabis');
        }else{
            //return view lomba dan data dalam bentuk object
            return view('peserta.lomba.form-wdc', ['user' => $user, 'data_peserta' => $data_peserta]);
        }
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
        $filename = "WDC_Foto Identitas_".$request->nama_lengkap."_".time().".".$foto->getClientOriginalExtension(); // format nama file
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
            'foto' => $path,
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
            DB::rollBack();
            throw $th;
        }
        return redirect('/lomba-peserta');
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
            $filename = "WDC_Bukti Transfer_" . $namaPeserta . '_' .time(). '.' . $foto->getClientOriginalExtension();
            $path = 'transfer/' . $filename;
            // simpan foto ke storage
            Storage::disk('public')->put($path, file_get_contents($foto));
            return $path;
        }
        // error message jika gagal upload foto
        return redirect()->back()->with('error', 'Gagal mengunggah foto.');
    }

    public function formProjectWdc(Request $request, $id)
    {
        // cari data yang login (gk perlu dirubah)
        $user = Auth::user();
        $peserta = Peserta::where('email', $user->email)->first();
        $namaPeserta = $peserta->nama_lengkap;
        $idPeserta = $peserta->id_peserta;
        // cari peserta wdc yang login
        $pesertaWdc = Wdc::where('id_peserta', $idPeserta)->first();
        // cek apakah peserta sudah upload project
        if($pesertaWdc->id_project != null){
            $idProjectSebelumnya = $pesertaWdc->id_project;
            // ambil nama file project sebelumnya
            $projectSebelumnya = DB::table('project')->where('id_project', $idProjectSebelumnya)->first();
            $namaProjectSebelumnya = $projectSebelumnya->file_project;
        }

        // validasi file (gk perlu diubah)
        $request->validate([
            'project' => 'required|mimes:rar,zip|max:100000'
        ],[
            'project.mimes' => 'Format file project harus berupa rar atau zip.',
            'project.max' => 'Ukuran file project maksimal 100 MB.'
        ]);

        // ambil nama file untuk disimpan ke db
        $filePath = $this->uploadProjectWdc($request, $namaPeserta, true);
        // data yang akan di insert ke table transaksi
        $dataTransaksi = [
            'file_project' => $filePath,
            'created_at' => Carbon::now(),
        ];
        try{
            DB::beginTransaction();
            // insert data ke transaksi dan ambil idnya
            $idProject = DB::table('project')->insertGetId($dataTransaksi);
            // data yang akan di update ke table dc yaitu kolom id_transaksi aja
            $dataProject = ['id_project' => $idProject, 'updated_at' => Carbon::now()];
            // update kolom id_transaksi pada table dc (sesuaiin dengan cabang lomba)
            DB::table('wdc')->where('id_peserta', $idPeserta)->update($dataProject); 
            // jika data yang baru berhasil disimpan, hapus data project sebelumnya jika ada
            if(isset($idProjectSebelumnya)){
                DB::table('project')->where('id_project', $idProjectSebelumnya)->delete();
            }
            DB::commit();
            // jika transaksi berhasil, hapus file project sebelumnya jika ada
            if(isset($namaProjectSebelumnya)){
                Storage::disk('public')->delete('Project/wdc/' . $namaProjectSebelumnya);
            }
            return redirect('/lomba-peserta')->with('success', 'Berhasil mengunggah project');
        } catch (\Throwable $th) {
            // jika terjadi error, hapus file yang sudah terupload
            Storage::disk('public')->delete($filePath);
            DB::rollBack();
            // get error message
            $error = $th->getMessage();
            // redirect
            return redirect('/lomba-peserta')->with('error', $error);
        }
    }

    public function uploadProjectWdc(Request $request, $namaPeserta)
    {
        if ($request->hasFile('project')) {
            $project = $request->file('project');
            // format nama dan path project sesuaiin dengan cabang lomba
            $filename = "WDC_project_" . $namaPeserta .'_'. time() .'.' . $project->getClientOriginalExtension();
            $path = 'Project/wdc/' . $filename;
            // simpan project ke storage
            Storage::disk('public')->put($path, file_get_contents($project));
            return $filename;
        }
        // error message jika gagal upload project
        return redirect()->back()->with('error', 'Gagal mengunggah project.');
    }

    public function downloadProjectWdc($filename)
    {
        // cek apakah file project ada
        $exists = Storage::disk('public')->exists('Project/wdc/' . $filename);
        if(!$exists){
            return redirect('lomba-peserta')->with('error', 'File tidak ditemukan.');
        }
        // jika peserta yang login memang benar mengupload file project, maka download file project
        $user = Auth::user();
        // cari data peserta yang login
        $peserta = Peserta::where('email', $user->email)->first();
        $idPeserta = $peserta->id_peserta;
        // cari id project peserta yang login
        $pesertaWdc = Wdc::where('id_peserta', $idPeserta)->first();
        $idProjectPeserta = $pesertaWdc->id_project;
        // cari id project yang akan didownload dari url
        $project = DB::table('project')->where('file_project', $filename)->first();
        $idProject = $project->id_project;
        // cek apakah benar peserta yang login mengupload file project yang akan didownload
        if($idProjectPeserta != $idProject){
            return redirect('lomba-peserta')->with('error', 'File tidak ditemukan.');
        }
        // download file project
        $filePath = public_path('storage/project/wdc/'.$filename);
        return response()->download($filePath);
    }

    // =========================================================================================== DC

    public function dc()
    {
        //mengambil data dari user yang login pada table users
        $user = Auth::user();
        //mengambil data dari table peserta yang memiliki email sama dengan user yang login 
        //pada table users dengan mencocokan email pada table peserta dengan table users
        $data_peserta = Peserta::where('email', $user->email)->first();

        $batasWaktuDC = new Carbon('2023-08-16 23:59:59');
        if($batasWaktuDC->isPast()){
            return view('errors.waktuHabis');
        }else{
            //return view lomba dan data dalam bentuk object
            return view('peserta.lomba.form-dc', ['user' => $user, 'data_peserta' => $data_peserta]);
        }
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
        $filename = "DC_Foto Identitas_".$request->nama_lengkap."_".time().".".$foto->getClientOriginalExtension(); // format nama file
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
            'foto' => $path,
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
            $filename = "DC_Bukti Transfer_" . $namaPeserta . '_' .time(). '.' . $foto->getClientOriginalExtension();
            $path = 'transfer/' . $filename;
            // simpan foto ke storage
            Storage::disk('public')->put($path, file_get_contents($foto));
            return $path;
        }
        // error message jika gagal upload foto
        return redirect()->back()->with('error', 'Gagal mengunggah foto.');
    }

    public function formProjectDc(Request $request, $id)
    {
        // cari data yang login (gk perlu dirubah)
        $user = Auth::user();
        $peserta = Peserta::where('email', $user->email)->first();
        $namaPeserta = $peserta->nama_lengkap;
        $idPeserta = $peserta->id_peserta;
        // cari peserta dc yang login
        $pesertaDc = Dc::where('id_peserta', $idPeserta)->first();
        // cek apakah peserta sudah upload project
        if($pesertaDc->id_project != null){
            $idProjectSebelumnya = $pesertaDc->id_project;
            // ambil nama file project sebelumnya
            $projectSebelumnya = DB::table('project')->where('id_project', $idProjectSebelumnya)->first();
            $namaProjectSebelumnya = $projectSebelumnya->file_project;
        }

        // validasi file (gk perlu diubah)
        $request->validate([
            'project' => 'required|mimes:rar,zip|max:100000'
        ],[
            'project.mimes' => 'Format file project harus berupa rar atau zip.',
            'project.max' => 'Ukuran file project maksimal 100 MB.'
        ]);
        // ambil nama file untuk disimpan ke db
        $filePath = $this->uploadProjectDc($request, $namaPeserta);
        // data yang akan di insert ke table transaksi
        $dataTransaksi = [
            'file_project' => $filePath,
            'created_at' => Carbon::now(),
        ];
        try{
            DB::beginTransaction();
            // insert data ke transaksi dan ambil idnya
            $idProject = DB::table('project')->insertGetId($dataTransaksi);
            // data yang akan di update ke table dc yaitu kolom id_transaksi aja
            $dataProject = ['id_project' => $idProject, 'updated_at' => Carbon::now()];
            // update kolom id_transaksi pada table dc (sesuaiin dengan cabang lomba)
            DB::table('dc')->where('id_peserta', $idPeserta)->update($dataProject); 
            // jika data yang baru berhasil disimpan, hapus data project sebelumnya jika ada
            if(isset($idProjectSebelumnya)){
                DB::table('project')->where('id_project', $idProjectSebelumnya)->delete();
            }
            DB::commit();
            // jika transaksi berhasil, hapus file project sebelumnya jika ada
            if(isset($namaProjectSebelumnya)){
                Storage::disk('public')->delete('Project/dc/' . $namaProjectSebelumnya);
            }
            return redirect('/lomba-peserta')->with('success', 'Berhasil mengunggah project');
        } catch (\Throwable $th) {
            // jika terjadi error, hapus file yang sudah terupload
            Storage::disk('public')->delete($filePath);
            DB::rollBack();
            // get error message
            $error = $th->getMessage();
            // redirect
            return redirect('/lomba-peserta')->with('error', $error);
        }
    }

    public function uploadProjectDc(Request $request, $namaPeserta)
    {
        if ($request->hasFile('project')) {
            $project = $request->file('project');
            // format nama dan path project sesuaiin dengan cabang lomba
            $filename = "DC_project_" . $namaPeserta .'_'. time() . '.' . $project->getClientOriginalExtension();
            $path = 'Project/dc/' . $filename;
            // simpan project ke storage
            Storage::disk('public')->put($path, file_get_contents($project));
            return $filename;
        }
        // error message jika gagal upload project
        return redirect()->back()->with('error', 'Gagal mengunggah project.');
    }

    public function downloadProjectDc($filename)
    {
        // cek apakah file project ada
        $exists = Storage::disk('public')->exists('Project/dc/' . $filename);
        if(!$exists){
            return redirect('lomba-peserta')->with('error', 'File tidak ditemukan.');
        }
        // jika peserta yang login memang benar mengupload file project, maka download file project
        $user = Auth::user();
        // cari data peserta yang login
        $peserta = Peserta::where('email', $user->email)->first();
        $idPeserta = $peserta->id_peserta;
        // cari id project peserta yang login
        $pesertaDc = Dc::where('id_peserta', $idPeserta)->first();
        $idProjectPeserta = $pesertaDc->id_project;
        // cari id project yang akan didownload dari url
        $project = DB::table('project')->where('file_project', $filename)->first();
        $idProject = $project->id_project;
        // cek apakah benar peserta yang login mengupload file project yang akan didownload
        if($idProjectPeserta != $idProject){
            return redirect('lomba-peserta')->with('error', 'File tidak ditemukan.');
        }
        // download file project
        $filePath = public_path('storage/project/dc/'.$filename);
        return response()->download($filePath);
    }

    // ====================================================================================== CTF
    public function ctf()
    {
        //mengambil data dari user yang login pada table users
        $user = Auth::user();
        //mengambil data dari table peserta yang memiliki email sama dengan user yang login 
        //pada table users dengan mencocokan email pada table peserta dengan table users
        $data_peserta = Peserta::where('email', $user->email)->first();

        $batasWaktuCTF = new Carbon('2023-08-26 23:59:59');
        if($batasWaktuCTF->isPast()){
            return view('errors.waktuHabis');
        }else{
            //return view lomba dan data dalam bentuk object
            return view('peserta.lomba.form-ctf', ['user' => $user, 'data_peserta' => $data_peserta]);
        }
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
        $filename = "CTF_Foto Identitas_".$request->nama_lengkap."_".time().".".$foto->getClientOriginalExtension(); // format nama file
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
            'foto' => $path,
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
            DB::rollBack();
            throw $th;
        }
        return redirect('/lomba-peserta');
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
            $filename = "CTF_Bukti Transfer_" . $namaPeserta . '_' .time(). '.' . $foto->getClientOriginalExtension();
            $path = 'transfer/' . $filename;
            // simpan foto ke storage
            Storage::disk('public')->put($path, file_get_contents($foto));
            return $path;
        }
        // error message jika gagal upload foto
        return redirect()->back()->with('error', 'Gagal mengunggah foto.');
    }

    //  public function formProjectCtf(Request $request, $id)
    // {
    //     // cari data yang login (gk perlu dirubah)
    //     $user = Auth::user();
    //     $peserta = Peserta::where('email', $user->email)->first();
    //     $namaPeserta = $peserta->nama_lengkap;
    //     $idPeserta = $peserta->id_peserta;

    //     // validasi file (gk perlu diubah)
    //     $request->validate([
    //         'project' => 'required|mimes:pdf'
    //     ],[
    //         'project.mimes' => 'Format file project harus berupa Pdf.'
    //         // 'project.max' => 'Ukuran file project maksimal 100 MB.'
    //     ]);
    //     // simpan project ke storage dan dapatkan pathnya (sesuaikan dengan nama fungsi)
    //     $filePath = $this->uploadProjectDc($request, $namaPeserta);
    //     try{
    //         DB::beginTransaction();
    //         // data yang akan di insert ke table transaksi
    //         $data = ['file_project' => $filePath];
    //         // insert data ke transaksi dan ambil idnya
    //         $idProject = DB::table('project')->insertGetId($data);
    //         // data yang akan di update ke table dc yaitu kolom id_transaksi aja
    //         $data2 = ['id_project' => $idProject];
    //         // update kolom id_transaksi pada table dc (sesuaiin dengan cabang lomba)
    //         DB::table('ctf')->where('id_peserta', $idPeserta)->update($data2); 
    //         DB::commit();
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         throw $th;
    //     }

    //     // redirect
    //     return redirect('/lomba-peserta');
    // }

    // public function uploadProjectCtf(Request $request, $namaPeserta)
    // {
    //     if ($request->hasFile('project')) {
    //         $project = $request->file('project');
    //         // format nama dan path project sesuaiin dengan cabang lomba
    //         $filename = "CTF_project_" . $namaPeserta .'_'. time() . '.' . $project->getClientOriginalExtension();
    //         $path = 'project/ctf/' . $filename;
    //         // simpan project ke storage
    //         Storage::disk('public')->put($path, file_get_contents($project));
    //         return $path;
    //     }
    //     // error message jika gagal upload project
    //     return redirect()->back()->with('error', 'Gagal mengunggah project.');
    // }
}
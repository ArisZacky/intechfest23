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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Compilers\Concerns\CompilesRawPhp;
use Illuminate\Support\Facades\Storage;

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
        $lomba = null;
        $batasWaktu = new Carbon('2023-09-15 23:59:59');

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
        } // jika sudah mendaftar tapi belum divalidasi CTF (step 2)
        else if($peserta->validasi == "Belum Tervalidasi" AND $lomba == "CTF"){
            return view('peserta.lomba.validasi_fomulir_ctf', compact('peserta'));
        }// jika sudah mendaftar tapi belum divalidasi WDC & DC (step 2)
        else if($peserta->validasi == "Belum Tervalidasi"){
            return view('peserta.lomba.validasi_formulir', compact('peserta'));
        }// jika sudah divalidasi tapi belum melakukan pembayaran untuk lomba dc (step 3)
        else if($peserta->validasi == "Sudah Valid" AND empty($peserta->id_transaksi) AND $lomba == "DC") {
            return view('peserta.lomba.transaksiDc', compact('peserta'));
        } // jika sudah divalidasi tapi belum melakukan pembayaran untuk lomba wdc (step 3)
        else if($peserta->validasi == "Sudah Valid" AND empty($peserta->id_transaksi) AND $lomba == "WDC") {
            return view('peserta.lomba.transaksiWdc', compact('peserta'));
        } // jika sudah divalidasi tapi belum melakukan pembayaran untuk lomba CTF (step 3)
        else if($peserta->validasi == "Sudah Valid" AND empty($peserta->id_transaksi) AND $lomba == "CTF") {
            return view('peserta.lomba.transaksiCtf', compact('peserta'));

        }else if(isset($peserta->id_transaksi) AND $lomba == "DC"){
            $transaksi = Transaksi::where('id_transaksi', $peserta->id_transaksi)->first();
            // jika sudah melakukan pembayaran dan belum divalidasi (step 4)
            if($transaksi->validasi == "Belum Tervalidasi"){
                return view('peserta.lomba.validasi_transaksi');
            }  // jika sudah mengupload project (step 6)
            else if($batasWaktu->isPast()) {
                return view('peserta.lomba.suksesProject');
            } // jika sudah melakukan pembayaran dan sudah divalidasi (step 5)
            else if($transaksi->validasi == "Sudah Valid"){
                $projectSebelumnya = null;
                if($peserta->id_project != null){
                    $idProjectSebelumnya = $peserta->id_project;
                    // ambil nama file project sebelumnya
                    $projectSebelumnya = DB::table('project')->where('id_project', $idProjectSebelumnya)->first();
                }
                return view('peserta.lomba.projectDc', compact('peserta', 'projectSebelumnya'));
            } 

        }else if(isset($peserta->id_transaksi) AND $lomba == "WDC"){
            $transaksi = Transaksi::where('id_transaksi', $peserta->id_transaksi)->first();
            // jika sudah melakukan pembayaran dan belum divalidasi (step 4)
            if($transaksi->validasi == "Belum Tervalidasi"){
                return view('peserta.lomba.validasi_transaksi');
            }  // jika sudah mengupload project (step 6)
            else if($batasWaktu->isPast()) {                
                return view('peserta.lomba.suksesProject');
            } // jika sudah melakukan pembayaran dan sudah divalidasi (step 5)
            else if($transaksi->validasi == "Sudah Valid"){
                $projectSebelumnya = null;
                if($peserta->id_project != null){
                    $idProjectSebelumnya = $peserta->id_project;
                    // ambil nama file project sebelumnya
                    $projectSebelumnya = DB::table('project')->where('id_project', $idProjectSebelumnya)->first();
                }
                return view('peserta.lomba.projectWdc', compact('peserta', 'projectSebelumnya'));
            } 

        }else if(isset($peserta->id_transaksi) AND $lomba == "CTF"){
            $transaksi = Transaksi::where('id_transaksi', $peserta->id_transaksi)->first();
            // jika sudah melakukan pembayaran dan belum divalidasi (step 4)
            if($transaksi->validasi == "Belum Tervalidasi"){
                return view('peserta.lomba.validasi_transaksi_ctf');
            }  // jika sudah divalidasi project (step 5)
            else if($transaksi->validasi == "Sudah Valid") {
                return view('peserta.lomba.suksesValidasictf');
            } 
        }else {
            return view('peserta.content.lomba');
        }
    }

    public function chilltalks()
    {
        $user = Auth::user();
        $data_peserta = Peserta::where('email', $user->email)->first();
        $id_peserta = $data_peserta->id_peserta;
        // cek jika table ct sudah ada data peserta dengan id yang sama
        $ctPeserta = Ct::where('id_peserta', $id_peserta)->first();
        // jika ctPeserta tidak ada datanya
        if(empty($ctPeserta)){
            return view('peserta.chilltalks.form-ct', compact('data_peserta'));
        } // jika ada ambil transaksi dan cek jika belum validasi
        else if(isset($ctPeserta->id_transaksi)){
            $transaksi = Transaksi::where('id_transaksi', $ctPeserta->id_transaksi)->first();
            if($transaksi->validasi == "Belum Tervalidasi"){
                return view('peserta.chilltalks.validasi_transaksi');
            } else if($transaksi->validasi == "Sudah Valid") {
                return view('peserta.chilltalks.nomor_peserta', compact('ctPeserta'));
            }
        }
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

     // DOWNLOAD GB WDC
     function downloadGuidebookWDC(){
        $file = Storage::download("public/guidebook/Guide Book Lomba PNBWDC.pdf");  
        return $file;
    }
     // DOWNLOAD GB WDC
     function downloadGuidebookDC(){
        $file = Storage::download("public/guidebook/Guide Book Lomba PNBDC.pdf");  
        return $file;
    }
     // DOWNLOAD GB WDC
     function downloadGuidebookCTF(){
        $file = Storage::download("public/guidebook/Guide Book Lomba PNBCTF.pdf");  
        return $file;
    }

}
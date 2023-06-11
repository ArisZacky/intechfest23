<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panitia;
use App\Models\Peserta;
use App\Models\Wdc;
use App\Models\Ct;
use App\Models\Ctf;
use App\Models\DC;
use App\Models\Transaksi;

class AdminController extends Controller
{
    public function index(){
        return view('admin.content.dashboard');
    }

    // PANITIA START ============================================================
    // Halaman Setting Akun Panitia
    public function panitia(){
        $panitia = Panitia::all();
        return view('admin.setting_akun.panitia.dashpanit', compact(['panitia']));
    }
    // PANITIA END ============================================================
    
    
    // PESERTA ============================================================
    // Halaman Setting Akun Peserta
    public function peserta(){
        $peserta = Peserta::all();

        // return view('admin.setting_akun.peserta.dashpeserta', compact(['peserta']));
        return view('admin.content.peserta', compact(['peserta']));
    }
    // UPDATE PESERTA
    public function updatePeserta(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'email' => 'required',
                'nomer_peserta' => 'required',
                'nama_lengkap' => 'required',
                'alamat' => 'required',
                'nama_instansi' => 'required',
                'no_hp' => 'required'
            ]);
            
            $data = $request->all();
            Peserta::where(['id_peserta' => $request->id_peserta])->update([
                'email'=>$data['email'], 
                'nomer_peserta'=>$data['nomer_peserta'], 
                'nama_lengkap'=>$data['nama_lengkap'],
                'alamat'=>$data['alamat'], 
                'nama_instansi'=>$data['nama_instansi'], 
                'no_hp'=>$data['no_hp']
        ]);
            return redirect()->back()->with('update_success', 'Update Data Berhasil!');
        }
    }
    // DELETE PESERTA
    public function deletePeserta(Request $request)
    {      
        $data = Peserta::findOrFail($request['id_peserta']);
        $data->delete();
        return redirect()->back()->with('delete_success', 'delete Data Berhasil!');        
    }   
    // MENAMPILKAN PESERTA YANG TER DELETE
    public function getDeletedPeserta()
    {
        $peserta = Peserta::onlyTrashed()->get();
        return view ('', compact(['peserta']));
    }
    // Restore (kembalikan data)
    public function restorePeserta($id){
        $peserta = Peserta::withTrashed()->where('id_peserta', $id)->restore();
        return redirect('');
    }
    // PESERTA END ============================================================

    // CT START ============================================================
    // halaman utama childtalks
    public function ct()
    {
        $ct = Ct::all();
        return view('admin.childtalk.dashct', compact(['ct']));
    }
    // UPDATE PESERTA
    public function updateCt(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'id_peserta' => 'required',
                'id_transaksi' => 'required',
            ]);
            
            $data = $request->all();
            Ct::where(['id_ct' => $request->id_ct])->update([
                'id_peserta'=>$data['id_peserta'], 
                'id_transaksi'=>$data['id_transaksi'], 
        ]);
            return redirect()->back()->with('update_success', 'Update Data Berhasil!');
        }
    }
    // DELETE PESERTA
    public function deleteCt(Request $request)
    {
        $data = Ct::findOrFail($request['id_ct']);
        $data->delete();
        return redirect()->back()->with('delete_success', 'delete Data Berhasil!');    
    }
    // MENAMPILKAN CT YANG TER DELETE
    public function getDeletedCt()
    {
        $ct = Ct::onlyTrashed()->get();
        return view ('', compact(['ct']));
    }
    // Restore (kembalikan data)
    public function restoreCt($id){
        $peserta = Ct::withTrashed()->where('id_ct', $id)->restore();
        return redirect('');
    }
    // CT END ============================================================

    // WDC START============================================================
    // halaman wdc
    public function wdc()
    {
        $wdc = Wdc::all();
        return view('admin.wdc.dashwdc', compact(['wdc']));
    }
    // UPDATE WDC
    public function updateWdc(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'id_peserta' => 'required',
                'foto' => 'required',
                'id_transaksi' => 'required',
                'id_project' => 'required',
                'validasi' => 'required',
            ]);
            
            $data = $request->all();
            Wdc::where(['id_wdc' => $request->id_wdc])->update([
                'id_peserta'=>$data['id_peserta'], 
                'foto'=>$data['foto'], 
                'id_transaksi'=>$data['id_transaksi'],
                'id_project'=>$data['id_project'], 
                'validasi'=>$data['validasi'], 
        ]);
            return redirect()->back()->with('update_success', 'Update Data Berhasil!');
        }
    }
    // DELETE WDC
    public function deleteWdc(Request $request)
    {
        $data = Wdc::findOrFail($request['id_wdc']);
        $data->delete();
        return redirect()->back()->with('delete_success', 'Delete Data Berhasil');
    }
    // MENAMPILKAN DATA WDC YANG TER DELETE 
    public function getDeletedWdc()
    {
        $wdc = Wdc::onlyTrashed()->get();
        return view ('', compact(['wdc']));
    }
    // Restore (kembalikan data)
    public function restoreWdc($id)
    {
        $wdc = Wdc::withTrashed()->where('id_wdc', $id)->restore();
        redirect('');
    }
    // WDC END============================================================

    // DC START============================================================
    // halaman dc
    public function dc()
    {
        $dc = Dc::all();
        return view('admin.dc.dashdc', compact(['dc']));
    }
    // UPDATE DC 
    public function updateDc()
    {
        if($request->isMethod('post')){
            $request->validate([
                'id_peserta' => 'required',
                'foto' => 'required',
                'id_transaksi' => 'required',
                'id_project' => 'required',
                'validasi' => 'required',
            ]);
            
            $data = $request->all();
            Dc::where(['id_dc' => $request->id_dc])->update([
                'id_peserta'=>$data['id_peserta'], 
                'foto'=>$data['foto'], 
                'id_transaksi'=>$data['id_transaksi'],
                'id_project'=>$data['id_project'], 
                'validasi'=>$data['validasi'], 
        ]);
            return redirect()->back()->with('update_success', 'Update Data Berhasil!');
        }
    }
    // DELETE DC 
    public function deleteDc(Request $request)
    {
        $data = Dc::findOrFail($request['id_dc']);
        $data->delete();
        return redirect()->back()->with('delete_success', 'Delete Data Berhasil');
    }
    // MENAMPILKAN DC YANG TER DELETE 
    public function getDeletedDc()
    {
        $dc = Dc::onlyTrashed()->get();
        return view('', compact(['dc']));
    }
    // Restore (Kembalikan data)
    public function restoreDc($id)
    {
        $dc = Dc::withTrashed()->where('id_dc', $id)->restore();
        redirect('');
    }
    // DC END============================================================

    // CTF START============================================================
    // halaman ctf
    public function ctf()
    {
        $ctf = Ctf::all();
        return view('admin.ctf.dashctf', compact(['ctf']));
    }
    // UPDATE CTF 
    public function updateCtf()
    {
        if($request->isMethod('post')){
            $request->validate([
                'id_peserta' => 'required',
                'nama_team' => 'required',
                'id_transaksi' => 'required',
                'id_project' => 'required',
                'anggota1' => 'required',
                'foto_1' => 'required',
                'anggota2' => 'required',
                'foto_2' => 'required',
                'validasi' => 'required',
            ]);
            
            $data = $request->all();
            Ctf::where(['id_ctf' => $request->id_ctf])->update([
                'id_peserta'=>$data['id_peserta'], 
                'nama_team'=>$data['nama_team'], 
                'id_transaksi'=>$data['id_transaksi'],
                'id_project'=>$data['id_project'], 
                'anggota1'=>$data['anggota1'],
                'foto_1'=>$data['foto_1'],
                'anggota2'=>$data['anggota2'],
                'foto_2'=>$data['foto_2'],
                'validasi'=>$data['validasi'], 
        ]);
            return redirect()->back()->with('update_success', 'Update Data Berhasil!');
        }
    }
    // DELETE CTF 
    public function deleteCtf(Request $request)
    {
        $data = Ctf::findOrFail($request['id_ctf']);
        $data->delete();
        return redirect()->back()->with('delete_success', 'Delete Data Berhasil');
    }
    // MENAMPILKAN CTF YANG TER DELETE 
    public function getDeletedCtf()
    {
        $ctf = Ctf::onlyTrashed()->get();
        return view('', compact(['ctf']));
    }
    // Restore (Kembalikan data)
    public function restoreCtf($id)
    {
        $ctf = Ctf::withTrashed()->where('id_ctf', $id)->restore();
        redirect('');
    }
    // CTF END============================================================

    // TRANSTAKSI START============================================================
    // halaman transaksi
    public function transaksi()
    {
        $transaksi = Transaksi::all();
        return view('admin.transaksi.dashtransaksi', compact(['transaksi']));
    }
    // UPDATE TRANSAKSI 
    public function updateTransaksi()
    {
        if($request->isMethod('post')){
            $request->validate([
                'id_panitia' => 'required',
                'foto' => 'required',
                'validasi' => 'required',
            ]);
            
            $data = $request->all();
            Transaksi::where(['id_dc' => $request->id_dc])->update([
                'id_panitia'=>$data['id_panitia'], 
                'foto'=>$data['foto'], 
                'validasi'=>$data['validasi'], 
        ]);
            return redirect()->back()->with('update_success', 'Update Data Berhasil!');
        }
    }
    // DELETE TRANSAKSI 
    public function deleteTransaksi(Request $request)
    {
        $data = Transaksi::findOrFail($request['id_transaksi']);
        $data->delete();
        return redirect()->back()->with('delete_success', 'Delete Data Berhasil');
    }
    // MENAMPILKAN TRANSAKSI YANG TER DELETE 
    public function getDeletedTransaksi()
    {
        $transaksi = Transaksi::onlyTrashed()->get();
        return view('', compact(['transaksi']));
    }
    // Restore (Kembalikan data)
    public function restoreTransaksi($id)
    {
        $transaksi = Transaksi::withTrashed()->where('id_transaksi', $id)->restore();
        redirect('');
    }

    // TRANSTAKSI END============================================================

}

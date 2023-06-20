<?php

namespace App\Http\Controllers;

use App\Models\Ct;
use App\Models\DC;
use App\Models\Ctf;
use App\Models\Wdc;
use App\Models\Panitia;
use App\Models\Peserta;
use App\Models\Project;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(){
        Auth()->user();
        return view('admin.content.dashboard');
    }

    // PANITIA START ============================================================
    // Halaman Setting Akun Panitia
    public function panitia(){
        $panitia = Panitia::all();
        return view('admin.content.panitia', compact(['panitia']));
    }
    // UPDATE PANITIA
    public function updatePanitia(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'email_panitia' => 'required',
                'nama_lengkap' => 'required',
                'foto' => 'required'
            ]);
            
            $data = $request->all();
            Panitia::where(['id_panitia' => $request->id_panitia])->update([
                'email_panitia'=>$data['email_panitia'], 
                'nama_lengkap'=>$data['nama_lengkap'],
                'foto'=>$data['foto'], 
        ]);
            return redirect()->back()->with('update_success', 'Update Data Berhasil!');
        }
    }
    // DELETE PANITIA
    public function deletePanitia(Request $request)
    {      
        $data = Panitia::findOrFail($request['id_panitia']);
        $data->delete();
        return redirect()->back()->with('delete_success', 'delete Data Berhasil!');        
    }   
    // MENAMPILKAN PANITIA YANG TER DELETE
    public function getDeletedPanitia()
    {
        $panitia = Panitia::onlyTrashed()->get();
        return view ('', compact(['panitia']));
    }
    // Restore (kembalikan data)
    public function restorePanitia($id){
        $panitia = Panitia::withTrashed()->where('id_panitia', $id)->restore();
        return redirect('');
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
        $ct = Ct::          
        join('peserta', 'ct.id_peserta', '=', 'peserta.id_peserta')
        ->leftJoin('transaksi', 'ct.id_transaksi', '=', 'transaksi.id_transaksi')
        ->select('ct.*', 'peserta.*', 'transaksi.foto AS foto_transaksi')
        ->get();
        return view('admin.chilltalk.dashct', compact(['ct']));
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
        $wdc = Wdc::          
        join('peserta', 'wdc.id_peserta', '=', 'peserta.id_peserta')
        ->leftJoin('project', 'wdc.id_project', '=', 'project.id_project')
        ->leftJoin('transaksi', 'wdc.id_transaksi', '=', 'transaksi.id_transaksi')
        ->select('wdc.*', 'peserta.*', 'transaksi.foto AS foto_transaksi', 'project.file_project')
        ->get();
        return view('admin.wdc.dashwdc', compact(['wdc']));
    }
    // UPDATE WDC
    public function updateWdc(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'id_wdc' => 'required',
                'validasi' => 'required',
            ]);
            
            $data = $request->all();
            Wdc::where(['id_wdc' => $request->id_wdc])->update([
                'id_wdc'=>$data['id_wdc'], 
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
        $dc = Dc::          
        join('peserta', 'dc.id_peserta', '=', 'peserta.id_peserta')
        ->leftJoin('project', 'dc.id_project', '=', 'project.id_project')
        ->leftJoin('transaksi', 'dc.id_transaksi', '=', 'transaksi.id_transaksi')
        ->select('dc.*', 'peserta.*', 'transaksi.foto AS foto_transaksi', 'project.file_project')
        ->get();
        return view('admin.dc.dashdc', compact(['dc']));
    }
    // UPDATE DC 
    public function updateDc(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'id_dc' => 'required',
                'validasi' => 'required',
            ]);
            
            $data = $request->all();
            Dc::where(['id_dc' => $request->id_dc])->update([
                'id_dc'=>$data['id_dc'], 
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
        $ctf = Ctf::          
        join('peserta', 'ctf.id_peserta', '=', 'peserta.id_peserta')
        ->leftJoin('project', 'ctf.id_project', '=', 'project.id_project')
        ->leftJoin('transaksi', 'ctf.id_transaksi', '=', 'transaksi.id_transaksi')
        ->select('ctf.*', 'peserta.*', 'transaksi.foto AS foto_transaksi', 'project.file_project')
        ->get();
        return view('admin.ctf.dashctf', compact(['ctf']));
    }
    // UPDATE CTF 
    public function updateCtf(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'id_ctf' => 'required',
                'validasi' => 'required',
            ]);
            
            $data = $request->all();
            Ctf::where(['id_ctf' => $request->id_ctf])->update([
                'id_ctf'=>$data['id_ctf'], 
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
        $panitia = Panitia::all();
        return view('admin.transaksi.dashtransaksi', compact(['transaksi', 'panitia']));
    }
    // UPDATE TRANSAKSI 
    public function updateTransaksi(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'id_panitia' => 'required',
                'validasi' => 'required',
            ]);
            
            $data = $request->all();
            Transaksi::where(['id_transaksi' => $request->id_transaksi])->update([
                'id_panitia'=>$data['id_panitia'], 
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

    // PROJECT START=============================================================
    // halaman project
    public function project()
    {
        $project = Project::all();
        return view('admin.project.dashproject', compact(['project']));
    }
    // PROJECT END===============================================================
}

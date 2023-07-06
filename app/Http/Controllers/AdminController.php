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
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Exports\CtExcel;
use App\Exports\CtfExcel;
use App\Exports\DcExcel;
use App\Exports\WdcExcel;
use Maatwebsite\Excel\Facades\Excel;
use File;
use ZipArchive;

class AdminController extends Controller
{
    public function index(){
        Auth()->user();
        return view('admin.content.dashboard');
    }

    // PANITIA START ============================================================
    // Halaman Setting Akun Panitia
    public function panitia(Request $request){
        // MENDAPATKAN REQUEST SEARCH 
        $search = $request->search;
        
        // CEK JIKA SEARCH = TRUE
        if($search){
            $panitia = Panitia::where('nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();    
        }else{
            $panitia = Panitia::paginate(15);
        }

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
    public function getDeletedPanitia(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        if($search){
            $panitia = Panitia::onlyTrashed()->where('nama_lengkap', 'LIKE', '%'.$search.'%')
            ->paginate();
        }else{
            $panitia = Panitia::onlyTrashed()->paginate(15);
        }
        return view ('admin.content.dashdeletepanitia', compact(['panitia']));
    }
    // Restore (kembalikan data)
    public function restorePanitia(Request $request){
        $panitia = Panitia::withTrashed()->where('id_panitia', $request['id_panitia'])->restore();
        return redirect()->back()->with('restore_success', 'Restore Data Berhasil!');
    }
    // PANITIA END ============================================================
    
    
    // PESERTA ============================================================
    // Halaman Setting Akun Peserta
    public function peserta(Request $request){
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        // CEK JIKA SEARCH = TRUE
        if($search){
            $peserta = Peserta::where('nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();    
        }else{
            $peserta = Peserta::paginate(15);
        }

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
    public function getDeletedPeserta(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;
        if($search){
            $peserta = Peserta::onlyTrashed()->where('nama_lengkap', 'LIKE', '%'.$search.'%')
            ->paginate();
        }else{
            $peserta = Peserta::onlyTrashed()->paginate(15);
        }
        return view ('admin.content.dashdeletepeserta', compact(['peserta']));
    }
    // Restore (kembalikan data)
    public function restorePeserta(Request $request){
        $peserta = Peserta::withTrashed()->where('id_peserta', $request['id_peserta'])->restore();
        return redirect()->back()->with('restore_success', 'Restore Data Berhasil');
    }
    // PESERTA END ============================================================

    // CT START ============================================================
    // halaman utama childtalks
    public function ct(Request $request)
    {
        // MENDAPAKATKAN REQUEST SEARCH
        $search = $request->search;

        // CEK JIKA SEARCH = TRUE
        if($search){
            $ct = Ct::          
            join('peserta', 'ct.id_peserta', '=', 'peserta.id_peserta')
            ->join('transaksi', 'ct.id_transaksi', '=', 'transaksi.id_transaksi')
            ->select('ct.*', 'peserta.*', 'transaksi.foto AS foto_transaksi')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();    
        }else{
            $ct = Ct::          
            join('peserta', 'ct.id_peserta', '=', 'peserta.id_peserta')
            ->leftJoin('transaksi', 'ct.id_transaksi', '=', 'transaksi.id_transaksi')
            ->select('ct.*', 'peserta.*', 'transaksi.foto AS foto_transaksi')
            ->paginate(15);    
        }

        return view('admin.chilltalk.dashct', compact(['ct']));
    }
    public function ctExportExcel()
	{
		return Excel::download(new CtExcel, 'Chilltalks.xlsx');
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
        return redirect()->back()->with('delete_success', 'Delete Data Berhasil!');    
    }
    // MENAMPILKAN CT YANG TER DELETE
    public function getDeletedCt(Request $request)
    {
        // MENDAPAKATKAN REQUEST SEARCH
        $search = $request->search;
        
        if($search){
            $ct = Ct::onlyTrashed()
            ->join('peserta', 'ct.id_peserta', '=', 'peserta.id_peserta')
            ->leftJoin('transaksi', 'ct.id_transaksi', '=', 'transaksi.id_transaksi')
            ->select('ct.*', 'peserta.*', 'transaksi.foto AS foto_transaksi')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();    
        }else{
            $ct = Ct::onlyTrashed()
            ->join('peserta', 'ct.id_peserta', '=', 'peserta.id_peserta')
            ->leftJoin('transaksi', 'ct.id_transaksi', '=', 'transaksi.id_transaksi')
            ->select('ct.*', 'peserta.*', 'transaksi.foto AS foto_transaksi')
            ->paginate(15);
    
        }        
        return view ('admin.chilltalk.dashdelete', compact(['ct']));
    }
    // Restore (kembalikan data)
    public function restoreCt(Request $request){
        $peserta = Ct::withTrashed()->where('id_ct', $request['id_ct'])->restore();
        return redirect()->back()->with('restore_success', 'Restore Data Berhasil!');
    }
    // CT END ============================================================

    // WDC START============================================================
    // halaman wdc
    public function wdc(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        if($search){
            $wdc = Wdc::          
            join('peserta', 'wdc.id_peserta', '=', 'peserta.id_peserta')
            ->leftJoin('project', 'wdc.id_project', '=', 'project.id_project')
            // ->leftJoin('transaksi', 'wdc.id_transaksi', '=', 'transaksi.id_transaksi')
            ->select('wdc.*', 'peserta.*', 'project.file_project')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();    
        }else{
            $wdc = Wdc::          
            join('peserta', 'wdc.id_peserta', '=', 'peserta.id_peserta')
            ->leftJoin('project', 'wdc.id_project', '=', 'project.id_project')
            // ->leftJoin('transaksi', 'wdc.id_transaksi', '=', 'transaksi.id_transaksi')
            ->select('wdc.*', 'peserta.*', 'project.file_project')
            ->paginate(15);             
        }

        return view('admin.wdc.dashwdc', compact(['wdc']));   
    }
    public function WdcExportExcel()
	{
		return Excel::download(new WdcExcel, 'WDC.xlsx');
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
    public function getDeletedWdc(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        if($search){
            $wdc = Wdc::onlyTrashed()
            ->join('peserta', 'wdc.id_peserta', '=', 'peserta.id_peserta')
            ->leftJoin('project', 'wdc.id_project', '=', 'project.id_project')
            ->select('wdc.*', 'peserta.*', 'project.file_project')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();  
        }else{
            $wdc = Wdc::onlyTrashed()
            ->join('peserta', 'wdc.id_peserta', '=', 'peserta.id_peserta')
            ->leftJoin('project', 'wdc.id_project', '=', 'project.id_project')
            ->select('wdc.*', 'peserta.*', 'project.file_project')
            ->paginate(15);
        }
        return view ('admin.wdc.dashdelete', compact(['wdc']));
    }
    // Restore (kembalikan data)
    public function restoreWdc(Request $request)
    {
        $wdc = Wdc::withTrashed()->where('id_wdc', $request['id_wdc'])->restore();
        return redirect()->back()->with('restore_success', 'Restore Data Berhasil');
    }
    // WDC END============================================================

    // DC START============================================================
    // halaman dc
    public function dc(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        if($search){
            $dc = Dc::          
            join('peserta', 'dc.id_peserta', '=', 'peserta.id_peserta')
            ->leftJoin('project', 'dc.id_project', '=', 'project.id_project')
            ->select('dc.*', 'peserta.*', 'project.file_project')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate(); 
        }else{
            $dc = Dc::          
            join('peserta', 'dc.id_peserta', '=', 'peserta.id_peserta')
            ->leftJoin('project', 'dc.id_project', '=', 'project.id_project')
            ->select('dc.*', 'peserta.*', 'project.file_project')
            ->paginate(15);    
        }

        return view('admin.dc.dashdc', compact(['dc']));
    }
    public function dcExportExcel()
	{
		return Excel::download(new DcExcel, 'Dc.xlsx');
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
    public function getDeletedDc(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        if($search){
            $dc = DC::onlyTrashed()
            ->join('peserta', 'dc.id_peserta', '=', 'peserta.id_peserta')
            ->leftJoin('project', 'dc.id_project', '=', 'project.id_project')
            ->select('dc.*', 'peserta.*', 'project.file_project')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate(); 
        }
        else{
            $dc = DC::onlyTrashed()
            ->join('peserta', 'dc.id_peserta', '=', 'peserta.id_peserta')
            ->leftJoin('project', 'dc.id_project', '=', 'project.id_project')
            ->select('dc.*', 'peserta.*', 'project.file_project')
            ->paginate(); 
        }
        $dc = Dc::onlyTrashed()->paginate(15);
        return view('admin.dc.dashdelete', compact(['dc']));
    }
    // Restore (Kembalikan data)
    public function restoreDc(Request $request)
    {
        $dc = Dc::withTrashed()->where('id_dc', $request['id_dc'])->restore();
        return redirect()->back()->with('restore_success', 'Restore Data Berhasil');
    }
    // DC END============================================================

    // CTF START============================================================
    // halaman ctf
    public function ctf(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        if($search){
            $ctf = Ctf::          
            join('peserta', 'ctf.id_peserta', '=', 'peserta.id_peserta')
            ->select('ctf.*', 'peserta.*')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $ctf = Ctf::          
            join('peserta', 'ctf.id_peserta', '=', 'peserta.id_peserta')
            ->select('ctf.*', 'peserta.*')
            ->paginate(15);    
        }

        return view('admin.ctf.dashctf', compact(['ctf']));
    }
    public function ctfExportExcel()
	{
		return Excel::download(new CtfExcel, 'CTF.xlsx');
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
    public function getDeletedCtf(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;
        if($search){
            $ctf = Ctf::onlyTrashed()
            ->join('peserta', 'ctf.id_peserta', '=', 'peserta.id_peserta')
            ->select('ctf.*', 'peserta.*')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $ctf = Ctf::onlyTrashed()
            ->join('peserta', 'ctf.id_peserta', '=', 'peserta.id_peserta')
            ->select('ctf.*', 'peserta.*')
            ->paginate(15);
        }
        return view('admin.ctf.dashdelete', compact(['ctf']));
    }
    // Restore (Kembalikan data)
    public function restoreCtf($id)
    {
        $ctf = Ctf::withTrashed()->where('id_ctf', $id)->restore();
        return redirect()->back()->with('restore_success', 'Restore Data Berhasil');
    }
    // CTF END============================================================

    // TRANSTAKSI START============================================================
    // halaman transaksi wdc
    public function transaksiWdc(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        // MENDAPATKAN DATA PANITIA YANG AKAN MEMVERIFIKASI
        $panitia = Panitia::all();

        if($search){
            $transaksi = Transaksi::
            join('wdc', 'transaksi.id_transaksi', '=', 'wdc.id_transaksi')
            ->join('peserta', 'wdc.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $transaksi = Transaksi::
            join('wdc', 'transaksi.id_transaksi', '=', 'wdc.id_transaksi')
            ->join('peserta', 'wdc.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->paginate(15);
        }

        return view('admin.transaksi.dashtransaksi', compact(['transaksi', 'panitia']));
    }
    // halaman transaksi dc
    public function transaksiDc(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        // MENDAPATKAN DATA PANITIA YANG AKAN MEMVERIFIKASI
        $panitia = Panitia::all();

        if($search){
            $transaksi = Transaksi::
            join('dc', 'transaksi.id_transaksi', '=', 'dc.id_transaksi')
            ->join('peserta', 'dc.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $transaksi = Transaksi::
            join('dc', 'transaksi.id_transaksi', '=', 'dc.id_transaksi')
            ->join('peserta', 'dc.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->paginate(15);
        }

        return view('admin.transaksi.dashtransaksi', compact(['transaksi', 'panitia']));
    }
    // halaman transaksi ctf
    public function transaksiCtf(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        // MENDAPATKAN DATA PANITIA YANG AKAN MEMVERIFIKASI
        $panitia = Panitia::all();

        if($search){
            $transaksi = Transaksi::
            join('ctf', 'transaksi.id_transaksi', '=', 'ctf.id_transaksi')
            ->join('peserta', 'ctf.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $transaksi = Transaksi::
            join('ctf', 'transaksi.id_transaksi', '=', 'ctf.id_transaksi')
            ->join('peserta', 'ctf.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->paginate(15);
        }

        return view('admin.transaksi.dashtransaksi', compact(['transaksi', 'panitia']));
    }
    // halaman transaksi ct
    public function transaksiCt(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        // MENDAPATKAN DATA PANITIA YANG AKAN MEMVERIFIKASI
        $panitia = Panitia::all();

        if($search){
            $transaksi = Transaksi::
            join('ct', 'transaksi.id_transaksi', '=', 'ct.id_transaksi')
            ->join('peserta', 'ct.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $transaksi = Transaksi::
            join('ct', 'transaksi.id_transaksi', '=', 'ct.id_transaksi')
            ->join('peserta', 'ct.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->paginate(15);
        }

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
    // MENAMPILKAN TRANSAKSI WDC YANG TER DELETE 
    public function getDeletedTransaksiWdc(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        if($search){
            $transaksi = Transaksi::onlyTrashed()
            ->join('wdc', 'transaksi.id_transaksi', '=', 'wdc.id_transaksi')
            ->join('peserta', 'wdc.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $transaksi = Transaksi::onlyTrashed()
            ->join('wdc', 'transaksi.id_transaksi', '=', 'wdc.id_transaksi')
            ->join('peserta', 'wdc.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->paginate();
        }
        return view('admin.transaksi.dashdelete', compact(['transaksi']));
    }
    // MENAMPILKAN TRANSAKSI DC YANG TER DELETE 
    public function getDeletedTransaksiDc(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        if($search){
            $transaksi = Transaksi::onlyTrashed()
            ->join('dc', 'transaksi.id_transaksi', '=', 'dc.id_transaksi')
            ->join('peserta', 'dc.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $transaksi = Transaksi::onlyTrashed()
            ->join('dc', 'transaksi.id_transaksi', '=', 'dc.id_transaksi')
            ->join('peserta', 'dc.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->paginate();
        }
        return view('admin.transaksi.dashdelete', compact(['transaksi']));
    }
    // MENAMPILKAN TRANSAKSI CTF YANG TER DELETE 
    public function getDeletedTransaksiCtf(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        if($search){
            $transaksi = Transaksi::onlyTrashed()
            ->join('ctf', 'transaksi.id_transaksi', '=', 'ctf.id_transaksi')
            ->join('peserta', 'ctf.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $transaksi = Transaksi::onlyTrashed()
            ->join('ctf', 'transaksi.id_transaksi', '=', 'ctf.id_transaksi')
            ->join('peserta', 'ctf.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->paginate();
        }
        return view('admin.transaksi.dashdelete', compact(['transaksi']));
    }
    // MENAMPILKAN TRANSAKSI CT YANG TER DELETE 
    public function getDeletedTransaksiCt(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        if($search){
            $transaksi = Transaksi::onlyTrashed()
            ->join('ct', 'transaksi.id_transaksi', '=', 'ct.id_transaksi')
            ->join('peserta', 'ct.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $transaksi = Transaksi::onlyTrashed()
            ->join('ct', 'transaksi.id_transaksi', '=', 'ct.id_transaksi')
            ->join('peserta', 'ct.id_peserta', '=', 'peserta.id_peserta')
            ->leftjoin('panitia', 'transaksi.id_panitia', '=', 'panitia.id_panitia')
            ->select('transaksi.*', 'peserta.nama_lengkap AS nama_peserta', 'panitia.nama_lengkap AS nama_panitia')
            ->paginate();
        }
        return view('admin.transaksi.dashdelete', compact(['transaksi']));
    }    
    // Restore (Kembalikan data)
    public function restoreTransaksi(Request $request)
    {
        $transaksi = Transaksi::withTrashed()->where('id_transaksi', $request['id_transaksi'])->restore();
        return redirect()->back()->with('restore_success', 'Restore Data Berhasil');
    }
    // TRANSTAKSI END============================================================

    // PROJECT START=============================================================
    // halaman project
    public function project()
    {
        $project = Project::all();
        return view('admin.project.dashproject', compact(['project']));
    }
    // HALAMAN PROJECT WDC
    public function projectWdc(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        if($search){
            $project = Project::
            join('wdc', 'project.id_project', '=', 'wdc.id_project')
            ->join('peserta', 'wdc.id_peserta', '=', 'peserta.id_peserta')
            ->select('project.*', 'peserta.*')
            ->where('file_project', 'LIKE', 'WDC%')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $project = Project::
            join('wdc', 'project.id_project', '=', 'wdc.id_project')
            ->join('peserta', 'wdc.id_peserta', '=', 'peserta.id_peserta')
            ->select('project.*', 'peserta.*')
            ->where('file_project', 'LIKE', 'WDC%')
            ->paginate(15);    
        }

        return view('admin.project.dashprojectwdc', compact(['project']));
    }
    // HALAMAN PROJECT DC 
    public function projectDc(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        if($search){
            $project = Project::
            join('dc', 'project.id_project', '=', 'dc.id_project')
            ->join('peserta', 'dc.id_peserta', '=', 'peserta.id_peserta')
            ->select('project.*', 'peserta.*')
            ->where('file_project', 'LIKE', 'DC%')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $project = Project::
            join('dc', 'project.id_project', '=', 'dc.id_project')
            ->join('peserta', 'dc.id_peserta', '=', 'peserta.id_peserta')
            ->select('project.*', 'peserta.*')
            ->where('file_project', 'LIKE', 'DC%')
            ->paginate(15);    
        }

        return view('admin.project.dashprojectdc', compact(['project']));
    }
    // HALAMAN PROJECT CTF
    public function projectCtf(Request $request)
    {
        // MENDAPATKAN REQUEST SEARCH
        $search = $request->search;

        if($search){
            $project = Project::
            join('ctf', 'project.id_project', '=', 'ctf.id_project')
            ->join('peserta', 'ctf.id_peserta', '=', 'peserta.id_peserta')
            ->select('project.*', 'peserta.*')
            ->where('file_project', 'LIKE', 'CTF%')
            ->where('peserta.nama_lengkap','LIKE','%'.$search.'%')
            ->paginate();
        }else{
            $project = Project::
            join('ctf', 'project.id_project', '=', 'ctf.id_project')
            ->join('peserta', 'ctf.id_peserta', '=', 'peserta.id_peserta')
            ->select('project.*', 'peserta.*')
            ->where('file_project', 'LIKE', 'CTF%')
            ->paginate(15);    
        }

        return view('admin.project.dashprojectctf', compact(['project']));
    }
    // DOWNLOAD PROJECT WDC SATU SATU
    function downloadProjectWDC($file_name){
        $file = response()->download(public_path('storage/Project/wdc/'.$file_name));  
        return $file;
    }

    // DOWNLOAD PROJECT DC SATU SATU
    function downloadProjectDC($file_name){
        $file = response()->download(public_path('storage/Project/dc/'.$file_name));
        return $file;
    }

    // // DOWNLOAD PROJECT CTF SATU SATU
    // function downloadProjectCTF($file_name){
    //     $file = Storage::download("public/Project/CTF/".$file_name);  
    //     return $file;
    // }

    // DOWNLOAD ALL PROJECT WDC
    function downloadAllProjectWDC()
    {
        // memanggil object zip archive dari laravel yang disimpan ke variabel
        $zip = new ZipArchive;
    
        // membuat nama file yang nantinya akan di download
        $fileName = 'ProjectWDC.zip';
     
        // mendeklarasikan path yang akan di download
        $path = public_path('storage/Project/wdc');

        // cek jika variabel yang berisi object filearchive tadi berjalan dan membuat file zip
        if ($zip->open(public_path('storage/'.$fileName), ZipArchive::CREATE) === TRUE)
        {
            // mengambil file file yang ada di path
            $files = File::files($path);

            // perulangan untuk mengambil setiap file yang ada di path
            foreach ($files as $key => $value) {
                // mengambil nama file dari path lengkap filenya
                $relativeNameInZipFile = basename($value);
                // menambah file ke dalam zip
                $zip->addFile($value, $relativeNameInZipFile);
            }
               
            $zip->close();
        }
        
        // fucntion mereturn response yang mendownload zip tadi
        return response()->download(public_path('storage/'.$fileName));
    }

    // DOWNLOAD ALL PROJECT DC
    function downloadAllProjectDC()
    {
        // memanggil object zip archive dari laravel yang disimpan ke variabel
        $zip = new ZipArchive;
    
        // membuat nama file yang nantinya akan di download
        $fileName = 'ProjectDC.zip';
        
        // mendeklarasikan path yang akan di download
        $path = public_path('storage/Project/dc');

        // cek jika variabel yang berisi object filearchive tadi berjalan dan membuat file zip
        if ($zip->open(public_path('storage/'.$fileName), ZipArchive::CREATE) === TRUE)
        {
            // mengambil file file yang ada di path
            $files = File::files($path);

            // perulangan untuk mengambil setiap file yang ada di path
            foreach ($files as $key => $value) {
                // mengambil nama file dari path lengkap filenya
                $relativeNameInZipFile = basename($value);
                // menambah file ke dalam zip
                $zip->addFile($value, $relativeNameInZipFile);
            }
                
            $zip->close();
        }
        
        // fucntion mereturn response yang mendownload zip tadi
        return response()->download(public_path('storage/'.$fileName));
    }

    // // DOWNLOAD ALL PROJECT CTF
    // function downloadAllProjectCTF()
    // {
    //     // memanggil object zip archive dari laravel yang disimpan ke variabel
    //     $zip = new ZipArchive;
    
    //     // membuat nama file yang nantinya akan di download
    //     $fileName = 'ProjectCTF.zip';
     
    //     // mendeklarasikan path yang akan di download
    //     $path = public_path('storage/Project/CTF');

    //     // cek jika variabel yang berisi object filearchive tadi berjalan dan membuat file zip
    //     if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE)
    //     {
    //         // mengambil file file yang ada di path
    //         $files = File::files($path);

    //         // perulangan untuk mengambil setiap file yang ada di path
    //         foreach ($files as $key => $value) {
    //             // mengambil nama file dari path lengkap filenya
    //             $relativeNameInZipFile = basename($value);
    //             // menambah file ke dalam zip
    //             $zip->addFile($value, $relativeNameInZipFile);
    //         }
               
    //         $zip->close();
    //     }
        
    //     // fucntion mereturn response yang mendownload zip tadi
    //     return response()->download(public_path($fileName));
    // }
    
    // PROJECT END===============================================================
}

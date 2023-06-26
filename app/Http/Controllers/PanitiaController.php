<?php

namespace App\Http\Controllers;

use App\Models\Ct;
use App\Models\Dc;
use App\Models\Ctf;
use App\Models\Wdc;
use App\Models\Content;
use App\Models\Project;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Exports\CtExcel;
use App\Exports\WdcExcel;
use App\Exports\DcExcel;
use App\Exports\CtfExcel;
use Maatwebsite\Excel\Facades\Excel;

class PanitiaController extends Controller
{
    public function index()
    {
        Auth::user();
        return view('panitia.content.dashboard');
    }

// CT ============================================================
    // halaman utama childtalks
    public function ct()
    {
        $ct = Ct::          
        join('peserta', 'ct.id_peserta', '=', 'peserta.id_peserta')
        ->leftJoin('transaksi', 'ct.id_transaksi', '=', 'transaksi.id_transaksi')
        ->select('ct.*', 'peserta.*', 'transaksi.foto AS foto_transaksi')
        ->get();
        return view('panitia.chilltalk.dashct', compact(['ct']));
    }
    // Delete ct
    public function delete_ct(Request $request)
    {
        $data = Ct::findOrFail($request['id_ct']);
        $data->delete();
        return redirect()->back()->with('delete_success', 'delete Data Berhasil!');        
    }
    // Export Excel
    public function ctExportExcel()
	{
		return Excel::download(new CtExcel, 'Chilltalks.xlsx');
	}

// WDC ===========================================================

    // halaman wdc
    public function wdc()
    {
        $wdc = Wdc::          
        join('peserta', 'wdc.id_peserta', '=', 'peserta.id_peserta')
        ->leftJoin('project', 'wdc.id_project', '=', 'project.id_project')
        ->leftJoin('transaksi', 'wdc.id_transaksi', '=', 'transaksi.id_transaksi')
        ->select('wdc.*', 'peserta.*', 'transaksi.foto AS foto_transaksi', 'project.file_project')
        ->get();
        return view('panitia.wdc.dashwdc', compact(['wdc']));
    }
    // delete wdc
    public function delete_wdc(Request $request)
    {      
        $data = Wdc::findOrFail($request['id_wdc']);
        $data->delete();
        return redirect()->back()->with('delete_success', 'delete Data Berhasil!');        
    }   
    // Update Wdc
    public function update_wdc(Request $request)
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

    // Export Excel
    public function wdcExportExcel()
	{
		return Excel::download(new WdcExcel, 'Wdc.xlsx');
	}

    // DOWNLOAD WDC
    function downloadWDC($file_name){
        $file = Storage::download("public/Project/".$file_name);  
        return $file;
    }


// DC ========================================================== 

    // halaman dc
    public function dc()
    {
        $dc = Dc::          
        join('peserta', 'dc.id_peserta', '=', 'peserta.id_peserta')
        ->leftJoin('project', 'dc.id_project', '=', 'project.id_project')
        ->leftJoin('transaksi', 'dc.id_transaksi', '=', 'transaksi.id_transaksi')
        ->select('dc.*', 'peserta.*', 'transaksi.foto AS foto_transaksi', 'project.file_project')
        ->get();
        return view('panitia.dc.dashdc', compact(['dc']));
    }
    // menghapus
    public function delete_dc(Request $request)
    { 
        $data = Dc::findOrFail($request['id_dc']);
        $data->delete();
        return redirect()->back()->with('delete_success', 'delete Data Berhasil!');   
    }
    // Update Dc
    public function update_dc(Request $request)
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

    // Export Excel
    public function DcExportExcel()
	{
		return Excel::download(new DcExcel, 'Dc.xlsx');
	}

    // DOWNLOAD DC
    function downloadDC($file_name){
        $file = Storage::download("public/Project/".$file_name);  
        return $file;
    }

 

// CTF =========================================================

    // halaman ctf
    public function ctf()
    {
        $ctf = Ctf::          
        join('peserta', 'ctf.id_peserta', '=', 'peserta.id_peserta')
        ->leftJoin('project', 'ctf.id_project', '=', 'project.id_project')
        ->leftJoin('transaksi', 'ctf.id_transaksi', '=', 'transaksi.id_transaksi')
        ->select('ctf.*', 'peserta.*', 'transaksi.foto AS foto_transaksi', 'project.file_project')
        ->get();
        return view('panitia.ctf.dashctf', compact(['ctf']));
    }
    public function delete_ctf(Request $request)
    {
        $data = Ctf::findOrFail($request['id_ctf']);
        $data->delete();
        return redirect()->back()->with('delete_success', 'delete Data Berhasil!'); 
    }
    // Update Ctf
    public function update_ctf(Request $request)
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

    // Export Excel
    public function CtfExportExcel()
	{
		return Excel::download(new CtfExcel, 'Ctf.xlsx');
	}

    // DOWNLOAD DC
    function downloadCtf($file_name){
        $file = Storage::download("public/Project/".$file_name);  
        return $file;
    }

// TRANSAKSI ===================================================

    // halaman transaksi
    public function transaksi()
    {
        $transaksi = Transaksi::all();
        return view('panitia.transaksi.dashtransaksi', compact(['transaksi']));
    }
    // delete Transaksi
    public function delete_transaksi(Request $request)
    {
        $data = Transaksi::findOrFail($request['id_transaksi']);
        $data->delete();
        return redirect()->back()->with('delete_success', 'delete Data Berhasil!');   
    }
    // update transaksi
    public function update_transaksi(Request $request)
    {
        if($request->isMethod('post')){
            $request->validate([
                'id_panitia' => 'required',
                'foto' => 'required',
                'validasi' => 'required',
            ]);
            
            $data = $request->all();
            Transaksi::where(['id_transaksi' => $request->id_transaksi])->update([
                'validasi'=>$data['validasi'],
                
        ]);
            return redirect()->back()->with('update_success', 'Update Data Berhasil!');
        }
    }

// PROJECT =====================================================

    // halaman Project
    public function project()
    {
        $project = Project::all();
        return view('panitia.project.dashproject', compact(['project']));
    }
     // delete project
     public function delete_project(Request $request)
     {
         $data = Project::findOrFail($request['id_project']);
         $data->delete();
         return redirect()->back()->with('delete_success', 'delete Data Berhasil!');  
     }
}
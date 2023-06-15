<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ct;
use App\Models\Wdc;
use App\Models\Dc;
use App\Models\Ctf;
use App\Models\Transaksi;
use App\Models\Project;
use App\Models\Content;

class PanitiaController extends Controller
{
    public function index()
    {
        return view('panitia.content.dashboard');
    }

// CT ============================================================
    // halaman utama childtalks
    public function ct()
    {
        $ct = Ct::all();    
        return view('panitia.chilltalk.dashct', compact(['ct']));
    }
    // Delete ct
    public function delete_ct(Request $request)
    {
        $data = Ct::findOrFail($request['id_ct']);
        $data->delete();
        return redirect()->back()->with('delete_success', 'delete Data Berhasil!');        
    }

// WDC ===========================================================

    // halaman wdc
    public function wdc()
    {
        $wdc = Wdc::all();
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
                'id_peserta' => 'required',
                'foto' => 'required',
                'id_transaksi' => 'required',
                'id_project' => 'required',
                'validasi' => 'required',
            ]);
            
            $data = $request->all();
            Wdc::where(['id_wdc' => $request->id_wdc])->update([
                'foto'=>$data['foto'],
                'validasi'=>$data['validasi'],
                
        ]);
            return redirect()->back()->with('update_success', 'Update Data Berhasil!');
        }
    }


// DC ========================================================== 

    // halaman dc
    public function dc()
    {
        $dc = Dc::all();
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
                'id_peserta' => 'required',
                'foto' => 'required',
                'id_transaksi' => 'required',
                'id_project' => 'required',
                'validasi' => 'required',
            ]);
            
            $data = $request->all();
            Dc::where(['id_dc' => $request->id_dc])->update([
                'foto'=>$data['foto'],
                'validasi'=>$data['validasi'],
                
        ]);
            return redirect()->back()->with('update_success', 'Update Data Berhasil!');
        }
    }
 

// CTF =========================================================

    // halaman ctf
    public function ctf()
    {
        $ctf = Ctf::all();
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
            ctf::where(['id_ctf' => $request->id_ctf])->update([
                'nama_team'=>$data['nama_team'],
                'anggota1'=>$data['anggota1'],
                'foto_1'=>$data['foto_1'],
                'anggota2'=>$data['anggota2'],
                'foto_2'=>$data['foto_2'],
                'validasi'=>$data['validasi'],
                
        ]);
            return redirect()->back()->with('update_success', 'Update Data Berhasil!');
        }
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
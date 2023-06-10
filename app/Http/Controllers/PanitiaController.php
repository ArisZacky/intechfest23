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
        return view('panitia.childtalk.dashct', compact(['ct']));
    }
    // Delete ct
    public function delete_ct($id)
    {
        $ct = Ct::findOrFail($id);
        $ct->delete();
        return redirect('/childtalk-panitia');
    }
    // menampilkan data yang sudah di hapus dc
    public function ct_hapus(){
        $ct = Ct::onlyTrashed()->get();
        return view ('panitia.childtalk.dashdelete', compact(['ct']));
    }
    // Restore (kembalikan data)
    public function ct_kembali($id){
        $ct = Ct::withTrashed()->where('id_ct', $id)->restore();
        return redirect('/childtalk-panitia');
    }

// WDC ===========================================================

    // halaman wdc
    public function wdc()
    {
        $wdc = Wdc::all();
        return view('panitia.wdc.dashwdc', compact(['wdc']));
    }
    // delete wdc
    public function delete_wdc($id)
    {
        $wdc = Wdc::findOrFail($id);
        $wdc->delete();
        return redirect('/wdc-panitia');
    }
    // menampilkan data yang sudah di hapus wdc
    public function wdc_hapus(){
        $wdc = Wdc::onlyTrashed()->get();
        return view ('panitia.wdc.dashdelet', compact(['wdc']));
    }
    // Restore (kembalikan data)
    public function wdc_kembali($id){
        $wdc = Wdc::withTrashed()->where('id_wdc', $id)->restore();
        return redirect('/wdc-panitia');
    }



// DC ========================================================== 

    // halaman dc
    public function dc()
    {
        $dc = Dc::all();
        return view('panitia.dc.dashdc', compact(['dc']));
    }
    // menghapus
    public function delete_dc($id)
    {
        $dc = Dc::findOrFail($id);
        $dc->delete();
        return redirect('/dc-panitia');
    }
    // menampilkan data yang sudah di hapus dc
    public function dc_hapus(){
        $dc = Dc::onlyTrashed()->get();
        return view ('panitia.dc.dashdelet', compact(['dc']));
    }
    // Restore (kembalikan data)
    public function dc_kembali($id){
        $dc = Dc::withTrashed()->where('id_dc', $id)->restore();
        return redirect('/dc-panitia');
    }

// CTF =========================================================

    // halaman ctf
    public function ctf()
    {
        $ctf = Ctf::all();
        return view('panitia.ctf.dashctf', compact(['ctf']));
    }
    public function delete_ctf($id)
    {
        $ctf = Ctf::findOrFail($id);
        $ctf->delete();
        return redirect('/ctf-panitia');
    }
    // menampilkan data yang sudah di hapus dc
    public function ctf_hapus(){
        $ctf = Ctf::onlyTrashed()->get();
        return view ('panitia.ctf.dashdelete', compact(['ctf']));
    }
    // Restore (kembalikan data)
    public function ctf_kembali($id){
        $ctf = Ctf::withTrashed()->where('id_ctf', $id)->restore();
        return redirect('/ctf-panitia');
    }
// TRANSAKSI ===================================================

    // halaman transaksi
    public function transaksi()
    {
        $transaksi = Transaksi::all();
        return view('panitia.transaksi.dashtransaksi', compact(['transaksi']));
    }
    // delete dc
    public function delete_trans($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();
        return redirect('/transaksi-panitia');
    }
    // menampilkan data yang sudah di hapus wdc
    public function trans_hapus(){
        $transaksi = Transaksi::onlyTrashed()->get();
        return view ('panitia.transaksi.dashdelet', compact(['transaksi']));
    }
    // Restore (kembalikan data)
    public function trans_kembali($id){
        $transaksi = Transaksi::withTrashed()->where('id_transaksi', $id)->restore();
        return redirect('/transaksi-panitia');
    }

// PROJECT =====================================================

    // halaman Project
    public function project()
    {
        $project = Project::all();
        return view('panitia.project.dashproject', compact(['project']));
    }
     // delete project
     public function delete_project($id)
     {
         $project = Project::findOrFail($id);
         $project->delete();
         return redirect('/project-panitia');
     }
     // menampilkan data yang sudah di hapus wdc
     public function project_hapus(){
         $project = Project::onlyTrashed()->get();
         return view ('panitia.project.dashdelet', compact(['project']));
     }
     // Restore (kembalikan data)
     public function project_kembali($id){
         $project = Project::withTrashed()->where('id_project', $id)->restore();
         return redirect('/project-panitia');
    }   
}
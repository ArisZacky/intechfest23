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
        return view('admin.dashboard');
    }

    // Halaman Setting Akun Panitia
    public function panitia(){
        $panitia = Panitia::all();
        return view('admin.setting_akun.panitia.dashpanit', compact(['panitia']));
    }

    // Halaman Setting Akun Peserta
    public function peserta(){
        $peserta = Peserta::all();
        return view('admin.setting_akun.peserta.dashpeserta', compact(['peserta']));
    }
    // halaman utama childtalks
    public function ct()
    {
        $ct = Ct::all();
        return view('admin.childtalk.dashct', compact(['ct']));
    }
    // halaman wdc
    public function wdc()
    {
        $wdc = Wdc::all();
        return view('admin.wdc.dashwdc', compact(['wdc']));
    }
    // halaman dc
    public function dc()
    {
        $dc = Dc::all();
        return view('admin.dc.dashdc', compact(['dc']));
    }
    // halaman ctf
    public function ctf()
    {
        $ctf = Ctf::all();
        return view('admin.ctf.dashctf', compact(['ctf']));
    }
    // halaman transaksi
    public function transaksi()
    {
        $transaksi = Transaksi::all();
        return view('admin.transaksi.dashtransaksi', compact(['transaksi']));
    }

}

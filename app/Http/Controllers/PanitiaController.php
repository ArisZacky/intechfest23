<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ct;
use App\Models\Wdc;
use App\Models\Dc;
use App\Models\Ctf;
use App\Models\Transaksi;

class PanitiaController extends Controller
{
    public function index()
    {
        return view('panitia.dashboard');
    }

    // halaman utama childtalks
    public function ct()
    {
        $ct = Ct::all();
        return view('panitia.childtalk.dashct', compact(['ct']));
    }
    // halaman wdc
    public function wdc()
    {
        $wdc = Wdc::all();
        return view('panitia.wdc.dashwdc', compact(['wdc']));
    }
    // halaman dc
    public function dc()
    {
        $dc = Dc::all();
        return view('panitia.dc.dashdc', compact(['dc']));
    }
    // halaman ctf
    public function ctf()
    {
        $ctf = Ctf::all();
        return view('panitia.ctf.dashctf', compact(['ctf']));
    }
    // halaman transaksi
    public function transaksi()
    {
        $transaksi = Transaksi::all();
        return view('panitia.transaksi.dashtransaksi', compact(['transaksi']));
    }
}

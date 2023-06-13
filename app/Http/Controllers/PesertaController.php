<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ct;
use App\Models\Wdc;
use App\Models\Dc;
use App\Models\Ctf;
use App\Models\Transaksi;
use App\Models\Project;
use Illuminate\View\Compilers\Concerns\CompilesRawPhp;

class PesertaController extends Controller
{
    public function index(){
        return view('peserta.content.dashboard');
    }
    // halaman CT
        public function ct()
        {
            $ct = Ct::all();
            return view('peserta.chilltalk.dashct', compact(['ct']));
        }
    // halaman DC
        public function dc()
        {
            $dc = Dc::all();
            return view('peserta.dc.dashdc', compact(['dc']));
        }
    // halaman WDC
        public function wdc()
        {
            $wdc = Wdc::all();
            return view('peserta.wdc.dashwdc', compact(['wdc']));
        }

    // halaman CTF
        public function ctf()
        {
            $ctf = Ctf::all();
            return view('peserta.ctf.dashctf', compact(['ctf']));
        }
    
    // halaman TRANSAKSI
        public function transaksi()
        {
            $transaksi = Transaksi::all();
            return view('peserta.transaksi.dashtransaksi', compact(['transaksi']));
        }
    
    

}

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
}

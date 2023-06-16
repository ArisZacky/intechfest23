<?php

namespace App\Http\Controllers;

use App\Models\Ct;
use App\Models\Dc;
use App\Models\Ctf;
use App\Models\Wdc;
use App\Models\Peserta;
use App\Models\Project;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Compilers\Concerns\CompilesRawPhp;

class PesertaController extends Controller
{
    public function index(){
        Auth::user();
        return view('peserta.content.dashboard');
    }

    public function profil(){
        $peserta = Peserta::get();
        return view('peserta.content.profil');
    }

    public function lomba(){
        return view('peserta.content.lomba');
    }

    public function chilltalks(){
        return view('peserta.content.chilltalks');
    }
}

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

    public function profil(){
        return view('peserta.content.profil');
    }

    public function lomba(){
        return view('peserta.content.lomba');
    }

    public function chilltalks(){
        return view('peserta.content.chilltalks');
    }
}

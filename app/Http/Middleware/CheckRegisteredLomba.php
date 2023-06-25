<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Peserta;
use App\Models\Dc;
use App\Models\Ctf;
use App\Models\Wdc;
use Illuminate\Support\Facades\Auth;

class CheckRegisteredLomba
{
    public function handle($request, Closure $next)
    {
        $userEmail = Auth::user()->email;
        $pesertaId = Peserta::where('email', $userEmail)->first()->id_peserta;
        
        if ($this->isUserRegistered($pesertaId)) {
            return redirect('/lomba-peserta');
        }
        
        return $next($request);
    }
    
    private function isUserRegistered($pesertaId)
    {
        return $this->isRegisteredInWdc($pesertaId) || $this->isRegisteredInDc($pesertaId) || $this->isRegisteredInCtf($pesertaId);
    }

    private function isRegisteredInWdc($pesertaId)
    {
        return Wdc::where('id_peserta', $pesertaId)->exists();
    }

    private function isRegisteredInDc($pesertaId)
    {
        return Dc::where('id_peserta', $pesertaId)->exists();
    }

    private function isRegisteredInCtf($pesertaId)
    {
        return Ctf::where('id_peserta', $pesertaId)->exists();
    }
}
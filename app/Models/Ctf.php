<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ctf extends Model
{
    use HasFactory;

    protected $table = 'ctf';
    
    // peserta
    public function peserta(): HasOne
    {
        return $this->hasOne(Peserta::class, 'id_peserta', 'id_peserta');
    }
    // transaksi
    public function transaksi(): HasOne
    {
        return $this->hasOne(Transaksi::class, 'id_transaksi', 'id_transaksi');
    }

}

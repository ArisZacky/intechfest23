<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ctf extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_peserta',
        'nama_team',
        'anggota1',
        'anggota2',
        'foto',
        'id_project',
        'validasi',
    ];

    protected $table = 'ctf';
    protected $primaryKey = 'id_ctf';
    
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

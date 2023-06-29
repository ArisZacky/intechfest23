<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ct';
    protected $primaryKey = 'id_ct';
    protected $fillable = [
        'id_peserta',
        'id_transaksi',
        'nomer_peserta',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

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

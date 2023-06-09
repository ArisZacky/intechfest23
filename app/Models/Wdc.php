<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Wdc extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'wdc';
    protected $primaryKey = 'id_wdc';

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
    // project
    public function project(): HasOne
    {
        return $this->hasOne(Project::class, 'id_project', 'id_project');
    }

}

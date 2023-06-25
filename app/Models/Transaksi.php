<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaksi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'foto',
        'validasi'
    ];

    // panitia
    public function panitia(): HasOne
    {
        return $this->hasOne(Panitia::class, 'id_panitia', 'id_panitia');
    }

    // ct
    public function ct(): BelongsTo
    {
        return $this->belongsTo(Ct::class. 'id_transaksi', 'id_transaksi');
    }
    // wdc
    public function wdc(): BelongsTo
    {
        return $this->belongsTo(Wdc::class. 'id_transaksi', 'id_transaksi');
    }
    // dc
    public function dc(): BelongsTo
    {
        return $this->belongsTo(Dc::class. 'id_transaksi', 'id_transaksi');
    }
    // ctf
    public function ctf(): BelongsTo
    {
        return $this->belongsTo(Ctf::class. 'id_transaksi', 'id_transaksi');
    }
}

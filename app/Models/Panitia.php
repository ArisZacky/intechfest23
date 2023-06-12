<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Panitia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'panitia';
    protected $primaryKey = 'id_panitia';

    protected $fillable = [
        'email_panitia',
        'nama_lengkap',
        'foto'
    ];

    // transaksi
    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class. 'id_panitia', 'id_panitia');
    }
}

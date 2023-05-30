<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Panitia extends Model
{
    use HasFactory;

    protected $table = 'panitia';

    // transaksi
    public function transaksi(): BelongsTo
    {
        return $this->belongsTo(Transaksi::class. 'id_panitia', 'id_panitia');
    }
}

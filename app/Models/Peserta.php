<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peserta extends Model
{
    use HasFactory, SoftDeletes;

    
    protected $table = 'peserta';
    protected $primaryKey = 'id_peserta';
    
    protected $fillable = [
        'email',
        'nomer_peserta',
        'nama_lengkap',
        'alamat',
        'nama_instansi',
        'no_hp'
    ];
    // ct
    public function ct(): BelongsTo
    {
        return $this->belongsTo(Ct::class, 'id_peserta', 'id_peserta');
    }
    // wdc
    public function wdc(): BelongsTo
    {
        return $this->belongsTo(Wdc::class. 'id_peserta', 'id_peserta');
    }
    // dc
    public function dc(): BelongsTo
    {
        return $this->belongsTo(Dc::class. 'id_peserta', 'id_peserta');
    }
    // ctf
    public function ctf(): BelongsTo
    {
        return $this->belongsTo(Ctf::class. 'id_peserta', 'id_peserta');
    }
}

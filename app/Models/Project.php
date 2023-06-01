<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'project';
    protected $primaryKey = 'id_project';
    public $timestamps = false;

    // wdc
    public function wdc(): BelongsTo
    {
        return $this->belongsTo(Wdc::class. 'id_project', 'id_project');
    }
    // dc
    public function wc(): BelongsTo
    {
        return $this->belongsTo(Dc::class. 'id_project', 'id_project');
    }
}

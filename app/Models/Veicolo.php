<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Veicolo extends Model
{
    use HasFactory;

    protected $table = 'veicoli';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'azienda_id',
        'modello',
        'targa',
        'kmPercorsi',
    ];

    /**
     * Get the azienda that owns the veicolo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function azienda()
    {
        return $this->belongsTo(Azienda::class, 'azienda_id');
    }
}

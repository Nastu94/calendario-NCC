<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FoglioViaggio extends Model
{
    use HasFactory;

    protected $table = 'fogli_viaggio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'azienda_id',
        'veicolo_id',
        'prenotazione_id',
        'kmIniziali',
        'kmFinali',
        'numero',
    ];

    /**
     * Get the azienda associated with the foglio viaggio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function azienda()
    {
        return $this->belongsTo(Azienda::class);
    }

    /**
     * Get the veicolo associated with the foglio viaggio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function veicolo()
    {
        return $this->belongsTo(Veicolo::class);
    }

    /**
     * Get the prenotazione associated with the foglio viaggio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function prenotazione()
    {
        return $this->belongsTo(Prenotazione::class);
    }
}

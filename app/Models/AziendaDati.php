<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AziendaDati extends Model
{
    use HasFactory;

    protected $table = 'aziende_dati';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'azienda_id',
        'indirizzo',
        'cap',
        'citta',
        'provincia',
        'partita_iva',
        'codice_sdi',
        'codice_fiscale',
        'email',
        'cellulare',
    ];

    /**
     * Get the azienda that owns the azienda dati.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function azienda()
    {
        return $this->belongsTo(Azienda::class);
    }
}

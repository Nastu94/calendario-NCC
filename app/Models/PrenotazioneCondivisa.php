<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrenotazioneCondivisa extends Model
{
    use HasFactory;

    protected $table = 'prenotazioni_condivise';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prenotazione_id',
        'acceptor_id',
        'stato',
    ];

    /**
     * Get the prenotazione associated with the shared prenotazione.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function prenotazione()
    {
        return $this->belongsTo(Prenotazione::class, 'prenotazione_id');
    }

    /**
     * Get the user who accepted the shared prenotazione.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function acceptor()
    {
        return $this->belongsTo(User::class, 'acceptor_id');
    }
}

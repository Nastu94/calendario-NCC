<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prenotazione extends Model
{
    use HasFactory;

    protected $table = 'prenotazioni';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'nome',
        'cognome',
        'email',
        'cellulare',
        'residenza',
        'cittaResidenza',
        'cap',
        'azienda',
        'partitaiva',
        'codicesdi',
        'partenza',
        'cittaPartenza',
        'dataPartenza',
        'arrivo',
        'cittaArrivo',
        'passeggeri',
        'datiPasseggeri',
        'infoExtraNoleggio',
        'bagagli',
        'seggiolino',
        'codiceEsterno',
        'gestionePrenotazione',
    ];

    /**
     * Get the user that owns the prenotazione.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function utente()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the prenotazioni condivise related to the prenotazione.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function condivisioni()
    {
        return $this->hasOne(PrenotazioneCondivisa::class);
    }

    /**
     * Get the prenotazioni condivise related to the prenotazione.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function foglioViaggio()
    {
        return $this->hasOne(FoglioViaggio::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Azienda extends Model
{
    use HasFactory;

    protected $table = 'aziende';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
    ];

    /**
     * Get the azienda data associated with the azienda.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dati()
    {
        return $this->hasOne(AziendaDati::class);
    }

    /**
     * Get the users that belong to the azienda.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function utenti()
    {
        return $this->belongsToMany(User::class, 'azienda_user', 'azienda_id', 'user_id');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prenotazione;
use App\Models\PrenotazioneCondivisa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RegistroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($data)
    {
        $user = auth()->user();
        $dataFormatted = Carbon::createFromFormat('Y-m-d', $data)->startOfDay();
    
        $query = Prenotazione::with(['condivisioni.acceptor', 'utente']);
    
        if ($user->permessi->contains('nome', 'Azienda')) {
            $aziendaIds = $user->aziende->pluck('id');
            $query->where(function ($q) use ($aziendaIds) {
                $q->whereHas('utente.aziende', function ($q) use ($aziendaIds) {
                    $q->whereIn('id', $aziendaIds);
                });
                // Includi le prenotazioni condivise accettate da altri utenti della stessa azienda
                $q->orWhereHas('condivisioni', function ($q) use ($aziendaIds) {
                    $q->where('stato', 'accettata')
                      ->whereHas('acceptor.aziende', function ($subQ) use ($aziendaIds) {
                        $subQ->whereIn('id', $aziendaIds);
                      });
                });
            });
        } else {
            $query->where('user_id', $user->id);
            // Includi le prenotazioni condivise accettate dall'utente Autista
            $query->orWhereHas('condivisioni', function ($q) use ($user) {
                $q->where('acceptor_id', $user->id)
                  ->where('stato', 'accettata');
            });
        }
    
        $prenotazioni = $query->get();
    
        $eventiRegistro = $prenotazioni->flatMap(function ($prenotazione) use ($dataFormatted, $user) {
            $eventi = collect();
            
            // Controlla se l'utente appartiene alla stessa azienda dell'utente che ha inserito la prenotazione
            $belongsToSameCompany = $prenotazione->utente->aziende->pluck('id')->intersect($user->aziende->pluck('id'))->isNotEmpty();
    
            // Eventi di inserimento di prenotazioni
            if ($belongsToSameCompany && $prenotazione->created_at->toDateString() === $dataFormatted->toDateString()) {
                $eventi->push([
                    'tipo' => $prenotazione->gestionePrenotazione == 'personale' ? 'Inserimento prenotazione personale' : 'Inserimento prenotazione condivisa',
                    'data' => $prenotazione->created_at,
                    'dettagli' => 'Prenotazione inserita',
                    'prenotazione' => $prenotazione->toArray()
                ]);
            }
    
            // Eventi di modifica di prenotazioni inserite
            if ($belongsToSameCompany && $prenotazione->updated_at->toDateString() === $dataFormatted->toDateString() && $prenotazione->created_at != $prenotazione->updated_at) {
                $eventi->push([
                    'tipo' => 'Modifica',
                    'data' => $prenotazione->updated_at,
                    'dettagli' => 'Prenotazione modificata',
                    'prenotazione' => $prenotazione->toArray()
                ]);
            }
    
            // Eventi di accettazione di prenotazioni condivise
            if ($prenotazione->condivisioni && $prenotazione->condivisioni->updated_at->toDateString() === $dataFormatted->toDateString() && $prenotazione->condivisioni->created_at !== $prenotazione->condivisioni->updated_at) {
                $eventi->push([
                    'tipo' => 'Accettata prenotazione condivisa da ' . $prenotazione->utente->name,
                    'data' => $prenotazione->condivisioni->updated_at,
                    'dettagli' => 'Prenotazione accettata da ' . $prenotazione->condivisioni->acceptor->name,
                    'prenotazione' => $prenotazione->toArray()
                ]);
            }
    
            return $eventi;
        });
    
        $eventiRegistro = $eventiRegistro->sortBy('data');

        return view('registro.index', compact('eventiRegistro', 'data'));
    }
}
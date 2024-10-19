<?php

namespace App\Http\Controllers;

use App\Models\PrenotazioneCondivisa;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class PrenotazioneCondivisaController extends Controller
{
    /**
     * Display a listing of the shared prenotazioni.
     * Visualizza un elenco delle prenotazioni condivise.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $dueOrePiùTardi = Carbon::now()->addHours(2); // Calcola l'orario due ore nel futuro
    
        // Estrai le prenotazioni condivise che non sono state ancora accettate e sono future, escludendo quelle inserite dall'utente stesso
        $prenotazioniCondivise = PrenotazioneCondivisa::where('stato', 'condivisa')
            ->whereHas('prenotazione', function ($query) use ($user, $dueOrePiùTardi) {
                $query->where('dataPartenza', '>=', $dueOrePiùTardi)
                      ->where('user_id', '!=', $user->id);
            })
            ->with(['prenotazione.utente', 'prenotazione.utente.aziende']) // Carica le relazioni necessarie per ottimizzare le prestazioni
            ->get();
    
        // Ordina le prenotazioni condivise per data di partenza usando la funzione di raccolta
        $prenotazioniCondivise = $prenotazioniCondivise->sortBy(function ($prenotazioneCondivisa) {
            return $prenotazioneCondivisa->prenotazione->dataPartenza;
        });
    
        return view('prenotazioni.index-condivise', compact('prenotazioniCondivise'));
    }
    

    /**
     * Update the specified shared prenotazione in storage.
     * Aggiorna i dettagli di una prenotazione condivisa nel database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $prenotazioneCondivisa = PrenotazioneCondivisa::findOrFail($id);
    
        // Controllo se la prenotazione è già stata accettata
        if ($prenotazioneCondivisa->stato == 'accettata') {
            return back()->with('error', 'Questa prenotazione è già stata accettata.');
        }
    
        $prenotazioneCondivisa->update([
            'acceptor_id' => $user->id,
            'stato' => 'accettata'
        ]);
    
        return redirect()->route('prenotazioni.index-personali')->with('success', 'Prenotazione accettata correttamente.');
    }

    /**
     * Remove the specified shared prenotazione from storage.
     * Rimuove una prenotazione condivisa dal database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        PrenotazioneCondivisa::findOrFail($id)->delete();
        return redirect()->route('prenotazioni.index-personali')->with('success', 'Prenotazione condivisa deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\AziendaDati;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Mostra il form di modifica del profilo utente.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $azienda = $user->aziende()->first(); // Ottieni la prima azienda associata all'utente
        $datiAzienda = $azienda ? $azienda->dati : null; // Ottieni i dati aziendali se disponibili

        // Se non ci sono dati aziendali, crea un'istanza vuota di AziendaDati
        if (!$datiAzienda) {
            $datiAzienda = new AziendaDati();
        }

        return view('profile.edit', [
            'user' => $user,
            'azienda' => $azienda,
            'datiAzienda' => $datiAzienda
        ]);
    }

    /**
     * Aggiorna le informazioni del profilo dell'utente.
     *
     * @param \App\Http\Requests\ProfileUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $azienda = $user->aziende()->first(); // Ottieni la prima azienda associata all'utente

        // Se non esiste un'azienda associata, ritorna un errore
        if (!$azienda) {
            return Redirect::back()->withErrors(['msg' => 'Nessuna azienda associata all\'utente.']);
        }

        // Ottieni o crea i dati aziendali
        $aziendaDati = $azienda->dati ?? new AziendaDati(['azienda_id' => $azienda->id]);

        $aziendaDati->fill($request->validated()); // Compila i dati aziendali con quelli validati
        $aziendaDati->save(); // Salva i dati

        return Redirect::route('profile.edit')->with('status', 'Dati aziendali aggiornati con successo.');
    }

    /**
     * Genera un token personale unico per l'utente.
     *
     * Questo metodo controlla se l'utente autenticato ha già un token personale.
     * Se non lo ha, ne genera uno nuovo e lo salva nel database. Se l'utente
     * ha già un token, restituisce un errore.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function generateToken(Request $request)
    {
        $user = Auth::user();

        if (empty($user->public_token)) {
            $user->public_token = Str::random(60); // Genera un token casuale
            $user->save(); // Salva l'utente

            return back()->with('success', 'Link personale generato con successo!');
        }

        return back()->withErrors(['msg' => 'Il Link personale è già stato generato.']);
    }

    /**
     * Cancella l'account dell'utente.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Verifica la correttezza della password inserita
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // Ottieni l'utente autenticato
        $user = $request->user(); 
        
        // Logout l'utente
        Auth::logout(); 
        
        // Elimina l'utente
        $user->delete(); 

        // Invalida la sessione
        $request->session()->invalidate(); 
        
        // Regenera il token della sessione
        $request->session()->regenerateToken(); 

        // Reindirizza alla homepage
        return Redirect::to('/'); 
    }
}

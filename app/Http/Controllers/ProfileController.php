<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\AziendaDati;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        // Assumi che ogni utente ha un'azienda associata, ma se non esiste, passa valori nulli
        $azienda = $user->aziende()->first();
        $datiAzienda = $azienda ? $azienda->dati : null;
    
        // Se non ci sono dati aziendali, passa un'istanza vuota del modello AziendaDati
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
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $azienda = $user->aziende()->first();
    
        if (!$azienda) {
            return Redirect::back()->withErrors(['msg' => 'Nessuna azienda associata all\'utente.']);
        }
    
        $aziendaDati = $azienda->dati ?? new AziendaDati(['azienda_id' => $azienda->id]);
    
        $aziendaDati->fill($request->validated());
        $aziendaDati->save();
    
        return Redirect::route('profile.edit')->with('status', 'Dati aziendali aggiornati con successo.');
    }    

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

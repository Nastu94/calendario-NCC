<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Prenotazione;
use Illuminate\Http\Request;

class PrenotazioneEsternaController extends Controller
{
    /**
     * Mostra il form di creazione della prenotazione tramite un token pubblico.
     *
     * @param string $token Token pubblico unico per identificare l'autista.
     * @return \Illuminate\View\View
     */
    public function create($token)
    {
        // Trova l'utente basato sul token pubblico fornito
        $user = User::where('public_token', $token)->firstOrFail();

        // Assicurati che l'utente sia un autista prima di procedere
        if (!$user->permessi->contains('nome', 'Autista')) {
            abort(403, 'Accesso non autorizzato.');
        }

        // Passa l'utente alla vista per mostrare il form di prenotazione
        return view('prenotazioni.esterna.create', compact('user'));
    }

    /**
     * Salva la nuova prenotazione creata tramite il link pubblico.
     *
     * @param Request $request Dati della richiesta.
     * @param string $token Token pubblico unico per identificare l'autista.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $token = $request->route('token'); // Assumi che il token sia passato come parametro di route
        
        // Trova l'utente autista associato al token
        $autista = User::where('public_token', $token)->firstOrFail();
    
        // Validazione dei dati in arrivo
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'cellulare' => 'required|string',
            'residenza' => 'nullable|string',
            'cittaResidenza' => 'nullable|string',
            'cap' => 'nullable|string|max:10',
            'partenza' => 'required|string',
            'cittaPartenza' => 'required|string',
            'dataPartenza' => 'required|date',
            'arrivo' => 'required|string',
            'cittaArrivo' => 'required|string',
            'passeggeri' => 'required|integer|min:0',
            'bagagli' => 'required|integer|min:0',
            'seggiolino' => 'required|boolean',
            'datiPasseggeri' => 'nullable|string',
            'infoExtraNoleggio' => 'nullable|string',
            'azienda' => 'required|boolean',
            'partitaiva' => 'nullable|string|required_if:azienda,1',
            'codicesdi' => 'nullable|string|required_if:azienda,1',
        ]);

        // Crea la nuova prenotazione
        $prenotazione = new Prenotazione($validatedData);
        $prenotazione->user_id = $autista->id; // Assumi che la prenotazione sia sempre gestita dall'autista con il token
        $prenotazione->gestionePrenotazione = 'personale'; // Gestita sempre personalmente dall'autista
        $prenotazione->save();
    
        return redirect()->route('prenotazioni.esterna.create', ['token' => $token])->with('success', 'Prenotazione inserita con successo!');
    }    
}

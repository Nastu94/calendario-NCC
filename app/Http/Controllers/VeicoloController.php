<?php

namespace App\Http\Controllers;

use App\Models\Veicolo;
use Illuminate\Http\Request;

class VeicoloController extends Controller
{
    /**
     * Display a listing of the veicoli.
     * Visualizza un elenco di tutti i veicoli.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user(); // Recupera l'utente autenticato
        $veicoli = collect(); // Crea una collezione vuota per i veicoli
    
        // Verifica se l'utente appartiene ad un'azienda
        $azienda = $user->aziende->first();
        if ($azienda) {
            // Recupera i veicoli appartenenti all'azienda dell'utente
            $veicoli = Veicolo::where('azienda_id', $azienda->id)->get();
        }
    
        return view('veicoli.index', compact('veicoli'));
    }
     

    /**
     * Show the form for creating a new veicolo.
     * Mostra il form per la creazione di un nuovo veicolo.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('veicoli.create');
    }

    /**
     * Store a newly created veicolo in storage.
     * Salva un nuovo veicolo nel database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'modello' => 'required|string|max:255',
            'targa' => 'required|string|max:20|unique:veicoli,targa',
            'kmPercorsi' => 'required|integer'
        ]);
        
        // Recupera l'ID dell'azienda collegata all'utente
        $azienda_id = $request->user()->aziende()->first()->id; 
    
        // Aggiungi l'ID dell'azienda ai dati validati prima di salvarli
        $veicolo = new Veicolo([
            'azienda_id' => $azienda_id,
            'modello' => $request->modello,
            'targa' => $request->targa,
            'kmPercorsi' => $request->kmPercorsi
        ]);
    
        $veicolo->save();
    
        return redirect()->route('veicoli.index')->with('success', 'Veicolo creato con successo.');
    }
    

    /**
     * Display the specified veicolo.
     * Visualizza i dettagli di un veicolo specifico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $veicolo = Veicolo::findOrFail($id);
        return view('veicoli.show', compact('veicolo'));
    }

    /**
     * Show the form for editing the specified veicolo.
     * Mostra il form per modificare un veicolo specifico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $veicolo = Veicolo::findOrFail($id);
        return view('veicoli.edit', compact('veicolo'));
    }

    /**
     * Update the specified veicolo in storage.
     * Aggiorna i dettagli di un veicolo specifico nel database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $veicolo = Veicolo::findOrFail($id);
    
        // Validazione dei dati con la regola unique che ignora l'id corrente del veicolo per il campo targa
        $validated = $request->validate([
            'modello' => 'required|string|max:255',
            'targa' => 'required|string|max:20|unique:veicoli,targa,' . $veicolo->id,
            'kmPercorsi' => 'required|integer'
        ]);
    
        // Aggiornamento del veicolo con i dati validati
        $veicolo->update($validated);
    
        return redirect()->route('veicoli.index')->with('success', 'Veicolo aggiornato con successo.');
    }

    /**
     * Remove the specified veicolo from storage.
     * Rimuove un veicolo specifico dal database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Veicolo::findOrFail($id)->delete();
        return redirect()->route('veicoli.index')->with('success', 'Veicolo eliminato con successo.');
    }
}

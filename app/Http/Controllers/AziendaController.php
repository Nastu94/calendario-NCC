<?php

namespace App\Http\Controllers;

use App\Models\Azienda;
use App\Models\AziendaDati;
use Illuminate\Http\Request;

class AziendaController extends Controller
{
    /**
     * Display a listing of aziende.
     * Mostra un elenco di tutte le aziende con i relativi dettagli estesi.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Eager loading dei dati aziendali per ottimizzare le prestazioni delle query.
        $aziende = Azienda::with('dati')->get();
        return view('aziende.index', compact('aziende'));
    }

    /**
     * Show the form for creating a new azienda.
     * Mostra un form per inserire una nuova azienda e i relativi dati.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.create-azienda');
    }

    /**
     * Store a newly created azienda and its data in storage.
     * Salva una nuova azienda e i relativi dati dettagliati nel database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validazione dei dati in input per assicurare la correttezza.
        $request->validate([
            'nome' => 'required|string|max:255|unique:aziende,nome',
        ]);
    
        $azienda = new Azienda();
        $azienda->nome = $request->nome;
        $azienda->save();
    
        return redirect()->route('dashboard')->with('success', 'Azienda creata con successo.');
    }

    /**
     * Display the specified azienda.
     * Visualizza i dettagli di una specifica azienda.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Caricamento dell'azienda e dei suoi dati associati.
        $azienda = Azienda::with('dati')->findOrFail($id);
        return view('aziende.show', compact('azienda'));
    }

    /**
     * Show the form for editing the specified azienda.
     * Mostra un form per modificare i dettagli di una specifica azienda.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Recupero dell'azienda da modificare con i dati pre-caricati.
        $azienda = Azienda::with('dati')->findOrFail($id);
        return view('aziende.edit', compact('azienda'));
    }

    /**
     * Update the specified azienda and its data in storage.
     * Aggiorna i dettagli di una specifica azienda e i dati correlati nel database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validazione dei dati in input per assicurare la correttezza.
        $validated = $request->validate([
            'nome' => 'required|max:255',
            'indirizzo' => 'required',
            'cap' => 'required',
            'citta' => 'required',
            'provincia' => 'required',
            'partita_iva' => 'required',
            'codice_sdi' => 'required',
            'codice_fiscale' => 'required',
            'email' => 'required|email',
            'cellulare' => 'required',
        ]);

        // Aggiornamento dell'azienda e dei suoi dati.
        $azienda = Azienda::findOrFail($id);
        $azienda->update(['nome' => $validated['nome']]);
        $azienda->dati()->update($validated);

        return redirect()->route('admin.index-azienda')->with('success', 'Azienda updated successfully.');
    }

    /**
     * Remove the specified azienda from storage.
     * Rimuove un'azienda e i suoi dati correlati dal database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Eliminazione dell'azienda e pulizia dei dati associati attraverso il cascade delete.
        $azienda = Azienda::findOrFail($id);
        $azienda->delete();
        return redirect()->route('admin.index-azienda')->with('success', 'Azienda deleted successfully.');
    }
}

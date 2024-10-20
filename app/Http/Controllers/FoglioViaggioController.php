<?php

namespace App\Http\Controllers;

use App\Models\FoglioViaggio;
use App\Models\Prenotazione;
use App\Models\Veicolo;
use Illuminate\Http\Request;

class FoglioViaggioController extends Controller
{
    /**
     * Display a listing of the fogli di viaggio.
     * Visualizza un elenco di tutti i fogli di viaggio basati sui permessi dell'utente.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        
        // Inizia la query con una relazione pre-caricata per ottimizzare il recupero dei dati
        $query = FoglioViaggio::query()->with(['prenotazione', 'prenotazione.utente.aziende', 'prenotazione.condivisioni']);

        if ($user->permessi->contains('nome', 'Autista')) {
            // Per gli utenti Autista, recuperare i fogli di viaggio delle prenotazioni inserite dall'utente
            // e delle prenotazioni condivise accettate dall'utente
            $query->whereHas('prenotazione', function ($q) use ($user) {
                $q->where('user_id', $user->id)
                ->where('gestionePrenotazione', 'personale') // Solo prenotazioni personali
                ->orWhere(function ($q) use ($user) {
                    $q->whereHas('condivisioni', function ($q) use ($user) {
                        $q->where('acceptor_id', $user->id);
                    });
                });
            });
        } elseif ($user->permessi->contains('nome', 'Azienda')) {
            // Per gli utenti Azienda, recuperare i fogli di viaggio di tutte le prenotazioni
            // inserite dagli utenti della stessa azienda e le prenotazioni condivise accettate da utenti della stessa azienda
            $aziendaIds = $user->aziende->pluck('id');
            $query->whereHas('prenotazione', function ($q) use ($aziendaIds) {
                $q->whereHas('utente.aziende', function ($q) use ($aziendaIds) {
                    $q->whereIn('id', $aziendaIds); // Prenotazioni inserite dagli utenti della stessa azienda
                })
                ->where('gestionePrenotazione', 'personale')
                ->orWhereHas('condivisioni', function ($q) use ($aziendaIds) {
                    $q->where('stato', 'accettata')
                    ->whereHas('acceptor.aziende', function ($q) use ($aziendaIds) {
                        $q->whereIn('id', $aziendaIds);
                    });
                });
            });
        }

        // Aggiungi ordinamento decrescente per id per mostrare prima i fogli più recenti
        $fogliViaggio = $query->orderBy('id', 'desc')->get();

        return view('fogli-viaggio.index', compact('fogliViaggio'));
    }

    /**
     * Show the form for creating a new foglio di viaggio.
     * Mostra il form per la creazione di un nuovo foglio di viaggio.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = auth()->user();
    
        // Inizia la query per le prenotazioni
        $query = Prenotazione::with(['condivisioni.acceptor', 'utente'])
            // Assicurati che la prenotazione non abbia già un foglio di viaggio
            ->doesntHave('foglioViaggio');
    
        // Verifica se l'utente ha il permesso da Azienda
        if ($user->permessi->contains('nome', 'Azienda')) {
            $aziendaIds = $user->aziende->pluck('id');
            $query->where(function ($q) use ($aziendaIds) {
                $q->whereHas('utente.aziende', function ($subQ) use ($aziendaIds) {
                    $subQ->whereIn('azienda_id', $aziendaIds);
                })
                ->where('gestionePrenotazione', 'personale');
    
                // Include prenotazioni condivise inserite dagli utenti della stessa azienda che sono ancora in stato di condivisione
                $q->orWhereHas('condivisioni', function ($subQ) use ($aziendaIds) {
                    $subQ->where('stato', 'condivisa')
                        ->whereHas('acceptor', function ($query) use ($aziendaIds) {
                            $query->whereHas('aziende', function ($query) use ($aziendaIds) {
                                $query->whereIn('id', $aziendaIds);
                            });
                        });
                });
    
                // Include prenotazioni condivise accettate da utenti della stessa azienda
                $q->orWhereHas('condivisioni.acceptor.aziende', function ($subQ) use ($aziendaIds) {
                    $subQ->where('stato', 'accettata')
                         ->whereIn('id', $aziendaIds);
                });
            });
        } else {
            // Solo per Autisti: prendi le prenotazioni personali, le condivise accettate e le condivise non ancora accettate
            $query->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->where('gestionePrenotazione', 'personale')
                  ->orWhereHas('condivisioni', function ($subQ) use ($user) {
                      $subQ->where(function ($subQ) use ($user) {
                          $subQ->where('acceptor_id', $user->id) // Prenotazioni condivise accettate dall'utente
                               ->where('stato', 'accettata');
                      })
                      ->orWhere(function ($subQ) use ($user) {
                          $subQ->where('user_id', $user->id) // Prenotazioni condivise non ancora accettate
                               ->where('stato', 'condivisa');
                      });
                  });
            });
        }
    
        // Recupera le prenotazioni filtrate
        $prenotazioni = $query->get();
    
        // Recupera i veicoli collegati alla stessa azienda dell'utente autenticato
        $veicoli = Veicolo::whereHas('azienda', function ($q) use ($user) {
            $q->whereIn('id', $user->aziende->pluck('id'));
        })->get();
    
        // Passa le prenotazioni e i veicoli alla vista
        return view('fogli-viaggio.create', compact('prenotazioni', 'veicoli'));
    }
    
    /**
     * Store a newly created foglio di viaggio in storage.
     * Salva un nuovo foglio di viaggio nel database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
    
        // Recupera la prenotazione con le relative aziende e accettazioni
        $prenotazione = Prenotazione::with(['utente.aziende', 'condivisioni.acceptor.aziende'])
                        ->findOrFail($request->prenotazione_id);
    
        // Verifica se esiste già un foglio di viaggio
        if ($prenotazione->foglioViaggio()->exists()) {
            return back()->withErrors(['prenotazione_id' => 'Esiste già un foglio di viaggio per questa prenotazione.']);
        }
    
        // Verifica la corretta appartenenza della prenotazione
        $aziendaIds = $user->aziende->pluck('id');
        $isAuthorized = $user->permessi->contains('nome', 'Azienda') ?
                        $prenotazione->utente->aziende->pluck('id')->intersect($aziendaIds)->isNotEmpty() ||
                        $prenotazione->condivisioni->pluck('acceptor.aziende.id')->intersect($aziendaIds)->isNotEmpty() :
                        $prenotazione->user_id == $user->id || $prenotazione->condivisioni->pluck('acceptor_id')->contains($user->id);
    
        if (!$isAuthorized) {
            return back()->withErrors(['prenotazione_id' => 'Non autorizzato a creare un foglio di viaggio per questa prenotazione.']);
        }
    
        // Validazione e creazione del foglio di viaggio
        $validated = $request->validate([
            'veicolo_id' => 'required|exists:veicoli,id',
            'kmIniziali' => 'required|integer'
        ]);
    
        FoglioViaggio::create([
            'azienda_id' => $aziendaIds->first(),
            'veicolo_id' => $validated['veicolo_id'],
            'prenotazione_id' => $request->prenotazione_id,
            'kmIniziali' => $validated['kmIniziali'],
            'kmFinali' => null, 
            'numero' => null 
        ]);
    
        return redirect()->route('fogli-viaggio.index')->with('success', 'Foglio di viaggio creato con successo.');
    }
    
    /**
     * Display the specified foglio di viaggio.
     * Visualizza i dettagli di un foglio di viaggio specifico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = auth()->user();
        $foglioViaggio = FoglioViaggio::with(['azienda.dati', 'prenotazione', 'veicolo'])->findOrFail($id);
    
        // Controlla se il foglio di viaggio appartiene all'azienda dell'utente autenticato
        if (!in_array($foglioViaggio->azienda_id, $user->aziende->pluck('id')->toArray())) {
            abort(403, 'Accesso non autorizzato a questo foglio di viaggio.');
        }
    
        // Controlla se i dati aziendali sono presenti
        if (!$foglioViaggio->azienda || !$foglioViaggio->azienda->dati) {
            abort(403, 'Dati aziendali non disponibili. Impossibile visualizzare il foglio di viaggio.');
        }
    
        // Se tutto è in ordine, passa i dati alla vista
        return view('fogli-viaggio.show', compact('foglioViaggio'));
    }
       

    /**
     * Show the form for editing the specified foglio di viaggio.
     * Mostra il form per modificare un foglio di viaggio specifico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = auth()->user();
        $foglioViaggio = FoglioViaggio::with('prenotazione.utente.aziende')->findOrFail($id);
        $currentYear = date('Y');
    
        // Verifica se l'utente può modificare il foglio di viaggio
        $aziendaIds = $user->aziende->pluck('id');
    
        // Controllo diretto sull'appartenenza dell'azienda al foglio di viaggio
        if (!in_array($foglioViaggio->azienda_id, $aziendaIds->toArray())) {
            abort(403, 'Accesso non autorizzato a questo foglio di viaggio.');
        }

        // Verifica se il foglio di viaggio ha già un numero definitivo assegnato e non è modificabile
        if ($foglioViaggio->numero && strpos($foglioViaggio->numero, '/') !== false) {
            abort(403, 'Questo foglio di viaggio è già stato numerato e non può essere modificato ulteriormente.');
        }
    
        // Trova l'ultimo numero di foglio di viaggio usato per questa azienda
        $lastNumber = FoglioViaggio::where('azienda_id', $foglioViaggio->azienda_id)->max('numero');
        
        if ($lastNumber) {
            list($lastNumberOnly, $lastNumberYear) = explode('/', $lastNumber);
            $nextNumber = ($lastNumberYear == $currentYear) ? $lastNumberOnly + 1 : 1;
        } else {
            $nextNumber = 1;
        }
    
        $nextNumber = "$nextNumber/$currentYear";
    
        // Passa il numero successivo e il foglio di viaggio alla vista
        return view('fogli-viaggio.edit', compact('foglioViaggio', 'nextNumber'));
    }
    
    
    /**
     * Update the specified foglio di viaggio in storage.
     * Aggiorna i dettagli di un foglio di viaggio specifico nel database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $foglioViaggio = FoglioViaggio::with('veicolo')->findOrFail($id);
    
        $validated = $request->validate([
            'kmFinali' => 'required|integer|min:' . $foglioViaggio->kmIniziali, // Assicurati che i km finali non siano minori di quelli iniziali
            'numero' => 'required|string'
        ]);
    
        // Verifica se i km finali sono minori dei km iniziali
        if ($validated['kmFinali'] < $foglioViaggio->kmIniziali) {
            return back()->withErrors(['kmFinali' => 'I kilometri finali non possono essere minori dei kilometri iniziali.']);
        }
    
        // Aggiorna i dati del foglio di viaggio
        $foglioViaggio->update([
            'kmFinali' => $validated['kmFinali'],
            'numero' => $validated['numero']
        ]);
    
        // Aggiorna i km del veicolo
        if ($foglioViaggio->veicolo) {
            $foglioViaggio->veicolo->update(['kmPercorsi' => $validated['kmFinali']]);
        }
    
        return redirect()->route('fogli-viaggio.index')->with('success', 'Foglio di viaggio aggiornato con successo.');
    }
    
    /**
     * Remove the specified foglio di viaggio from storage.
     * Rimuove un foglio di viaggio specifico dal database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FoglioViaggio::findOrFail($id)->delete();
        return redirect()->route('fogli-viaggio.index')->with('success', 'Foglio di viaggio eliminato con successo.');
    }
}

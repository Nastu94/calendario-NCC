<?php

namespace App\Http\Controllers;

use App\Models\Prenotazione;
use App\Models\PrenotazioneCondivisa;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PrenotazioniController extends Controller
{
    /**
     * Display a listing of the prenotazioni.
     * Visualizza un elenco di tutte le prenotazioni.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $prenotazioniPerGiorno = Prenotazione::selectRaw('DATE(dataPartenza) as giorno, COUNT(*) as count')
            ->groupBy('giorno')
            ->get()
            ->map(function ($item) {
                return [
                    'title' => $item->count,
                    'start' => $item->giorno,
                    'allDay' => true,
                    'url' => route('prenotazioni.dettaglio', [$item->giorno, 'globali'])
                ];
            });
            return view('prenotazioni.index-globali', compact('prenotazioniPerGiorno'));
    }
    
    /**
     * Display a listing of the personal prenotazioni.
     * Visualizza un elenco di tutte le prenotazioni personali.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_personali()
    {
        $user = auth()->user(); // Assicurati che l'utente sia autenticato
    
        $prenotazioniPerGiorno = Prenotazione::query()
            ->where(function ($query) use ($user) {
                $query->where(function ($query) use ($user) {
                    // Prenotazioni inserite dall'utente con gestione personale
                    $query->where('user_id', $user->id)
                          ->where('gestionePrenotazione', 'personale');
                })
                ->orWhere(function ($query) use ($user) {
                    // Prenotazioni inserite dall'utente con gestione condivisa ma ancora in stato condivisa
                    $query->where('user_id', $user->id)
                          ->whereHas('condivisioni', function ($query) {
                              $query->where('stato', 'condivisa');
                          });
                })
                ->orWhere(function ($query) use ($user) {
                    // Prenotazioni non inserite dall'utente con gestione condivisa ma accettate dall'utente
                    $query->whereHas('condivisioni', function ($query) use ($user) {
                        $query->where('acceptor_id', $user->id)
                              ->where('stato', 'accettata');
                    });
                });
            })
            ->groupBy('dataPartenza')
            ->selectRaw('DATE(dataPartenza) as giorno, COUNT(*) as count')
            ->get()
            ->map(function ($item) {
                return [
                    'title' => $item->count,
                    'start' => $item->giorno,
                    'allDay' => true,
                    'url' => route('prenotazioni.dettaglio', [$item->giorno, 'personali'])
                ];
            });
    
        return view('prenotazioni.index-personali', compact('prenotazioniPerGiorno'));
    }
    
    /**
     * Display a listing of the prenotazioni.
     * Visualizza un elenco di tutte le prenotazioni aziendali.
     *
     * @return \Illuminate\Http\Response
     */
    public function index_aziendali()
    {
        $user = auth()->user();  // Assicurati che l'utente sia autenticato
        $aziendaIds = $user->aziende->pluck('id')->toArray();  // Recupera gli ID delle aziende associate all'utente
    
        $prenotazioniPerGiorno = Prenotazione::query()
            ->whereHas('utente.aziende', function ($query) use ($aziendaIds) {
                $query->whereIn('id', $aziendaIds);
            })
            ->where(function ($query) {
                $query->where('gestionePrenotazione', 'personale')
                      ->orWhere(function ($query) {
                          $query->where('gestionePrenotazione', 'condivisa')
                                ->whereHas('condivisioni', function ($query) {
                                    $query->where('stato', 'condivisa');
                                });
                      });
            })
            ->orWhere(function ($query) use ($aziendaIds) {
                $query->whereHas('condivisioni', function ($query) use ($aziendaIds) {
                    $query->where('stato', 'accettata')
                          ->whereHas('acceptor.aziende', function ($query) use ($aziendaIds) {
                              $query->whereIn('id', $aziendaIds);
                          });
                });
            })
            ->groupBy('dataPartenza')
            ->selectRaw('DATE(dataPartenza) as giorno, COUNT(*) as count')
            ->get()
            ->map(function ($item) {
                return [
                    'title' => $item->count,
                    'start' => $item->giorno,
                    'allDay' => true,
                    'url' => route('prenotazioni.dettaglio', [$item->giorno, 'aziendali'])
                ];
            });
    
        return view('prenotazioni.index-aziendali', compact('prenotazioniPerGiorno'));
    }
    
    /**
     * Show the form for creating a new prenotazione.
     * Mostra il form per la creazione di una nuova prenotazione, inclusa l'opzione per condividerla.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Recupera l'utente autenticato
        $user = auth()->user();  
        // Usa una funzione ausiliaria per filtrare le prenotazioni accessibili
        $prenotazioni = $this->fetchAccessiblePrenotazioni($user); 
        
        return view('prenotazioni.create', compact('prenotazioni'));
    }
    
    /**
     * Store a newly created prenotazione in storage.
     * Salva una nuova prenotazione nel database, inclusi i dettagli per prenotazioni condivise se applicabile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Converti la dataPartenza dal formato ISO 8601 al formato datetime di SQL
        $date = Carbon::createFromFormat('Y-m-d\TH:i', $request->dataPartenza)->format('Y-m-d H:i:s');
        $request->merge(['dataPartenza' => $date]);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'cellulare' => 'nullable|string|regex:/^\+?[1-9]\d{1,14}$/',
            'residenza' => 'nullable|string|max:255',
            'cittaResidenza' => 'nullable|string|max:255',
            'cap' => 'nullable|string|regex:/^\d{5}$/',
            'azienda' => 'required|boolean',
            'partitaiva' => 'nullable|required_if:azienda,1|string|max:20',
            'codicesdi' => 'nullable|string|max:20',
            'partenza' => 'required|string|max:255',
            'cittaPartenza' => 'required|string|max:255',
            'dataPartenza' => 'required|date_format:Y-m-d H:i:s',
            'arrivo' => 'required|string|max:255',
            'cittaArrivo' => 'required|string|max:255',
            'passeggeri' => 'nullable|integer|min:1',
            'datiPasseggeri' => 'nullable|string|max:10000',
            'bagagli' => 'nullable|integer|min:0',
            'seggiolino' => 'nullable|boolean',
            'codiceEsterno' => 'nullable|string|max:50',
            'infoExtraNoleggio' => 'nullable|string|max:10000',
            'gestionePrenotazione' => 'required|in:personale,condivisa',
        ]);
    
        // Aggiungi l'id dell'utente autenticato al array validated
        $validated['user_id'] = auth()->id();
    
        // Crea la prenotazione
        $prenotazione = new Prenotazione($validated);
        $prenotazione->save();
    
        // Verifica se la prenotazione deve essere condivisa
        if ($validated['gestionePrenotazione'] === 'condivisa') {
            PrenotazioneCondivisa::create([
                'prenotazione_id' => $prenotazione->id,
                'acceptor_id' => null,
                'stato' => 'condivisa'
            ]);
        }
    
        return redirect()->route('prenotazioni.index-personali')->with('success', 'Prenotazione inserita con successo.');
    }
    

    /**
     * Display the specified prenotazione.
     * Visualizza i dettagli di una specifica prenotazione.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $prenotazione = Prenotazione::findOrFail($id);
        return view('prenotazioni.show', compact('prenotazione'));
    }
    
    /**
     * Display the specified prenotazione.
     * Visualizza i dettagli delle prenotazioni per una specifica data.
     *
     * @param  mixed  $data
     * @return \Illuminate\Http\Response
     */
    public function showDailyPrenotazioni($data, $tipo)
    {
        $dataFormatted = Carbon::createFromFormat('Y-m-d', $data)->format('Y-m-d');
        $user = auth()->user();
        $aziendaIds = $user->aziende->pluck('id')->toArray();
    
        $query = Prenotazione::whereDate('dataPartenza', $dataFormatted)
                ->with(['utente', 'utente.aziende', 'condivisioni']); // Pre-caricamento delle relazioni per ottimizzare
    
        switch ($tipo) {
            case 'globali':
                // Nessun filtro specifico necessario per le prenotazioni globali
                break;        
            case 'aziendali':
                // Prenotazioni dell'azienda e condivisioni accettate dall'azienda
                $query->where(function($q) use ($aziendaIds) {
                    $q->whereHas('utente.aziende', function ($subQuery) use ($aziendaIds) {
                        $subQuery->whereIn('id', $aziendaIds);
                    });
                    $q->orWhereHas('condivisioni', function ($subQuery) use ($aziendaIds) {
                        $subQuery->where('stato', 'accettata')
                                 ->whereHas('acceptor.aziende', function ($subQuery) use ($aziendaIds) {
                                     $subQuery->whereIn('id', $aziendaIds);
                                 });
                    });
                });
                break;
            case 'personali':
                // Avvia la query filtrando per l'utente e la data
                $query->where('user_id', $user->id)
                        ->whereDate('dataPartenza', $dataFormatted);
            
                // Aggiungi le prenotazioni personali e condivise
                $query->where(function ($query) {
                    $query->where('gestionePrenotazione', 'personale')
                            ->orWhere(function ($query) {
                                $query->where('gestionePrenotazione', 'condivisa')
                                    ->whereHas('condivisioni', function ($query) {
                                        $query->where('stato', 'condivisa');
                                    });
                            });
                });
            
                // Aggiungi le prenotazioni condivise accettate dall'utente ma non necessariamente inserite da lui
                $query->orWhere(function ($query) use ($user, $dataFormatted) {
                    $query->whereHas('condivisioni', function ($query) use ($user, $dataFormatted) {
                        $query->where('stato', 'accettata')
                                ->where('acceptor_id', $user->id)
                                ->whereDate('dataPartenza', $dataFormatted);  // Assicura che la data corrisponda
                    });
                });
                break;                                            
            case 'condivise':
                // Filtra per prenotazioni condivise ancora non accettate e future
                $query->whereHas('condivisioni', function ($subQuery) use ($user) {
                    $subQuery->where('stato', 'condivisa')
                                ->where('user_id', '!=', $user->id)
                                ->where('dataPartenza', '>=', Carbon::now());
                });
                break;
            default:
                abort(404, 'Tipo di prenotazione non valido.');
        }
    
        $prenotazioni = $query->get();
        return view('prenotazioni.dettaglio', compact('prenotazioni', 'tipo', 'data'))->with('message', 'Dettagli prenotazioni per ' . $data);
    }
    
    /**
     * Show the form for editing the specified prenotazione.
     * Mostra il form per modificare una specifica prenotazione.
     *
     * @param  int  $id  The ID of the prenotazione to edit
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Recupera l'utente autenticato
        $user = auth()->user();
        // Trova la prenotazione o fallisce se non esiste
        $prenotazione = Prenotazione::findOrFail($id);

        // Utilizza la stessa logica del metodo `create` per determinare se la prenotazione è accessibile
        $accessiblePrenotazioni = $this->fetchAccessiblePrenotazioni($user);

        // Verifica se la prenotazione specificata è contenuta nell'elenco delle prenotazioni accessibili
        if (!$accessiblePrenotazioni->contains('id', $prenotazione->id)) {
            return redirect()->route('prenotazioni.index-personali')->with('error', 'Accesso non autorizzato a questa prenotazione.');
        }

        return view('prenotazioni.edit', compact('prenotazione'));
    }

    /**
     * Update the specified prenotazione in storage.
     * Aggiorna i dettagli di una specifica prenotazione nel database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $prenotazione = Prenotazione::findOrFail($id);
    
        // Converti la dataPartenza dal formato ISO 8601 al formato datetime di SQL
        $date = Carbon::createFromFormat('Y-m-d\TH:i', $request->dataPartenza)->format('Y-m-d H:i:s');
        $request->merge(['dataPartenza' => $date]);
    
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'cellulare' => 'nullable|string|regex:/^\+?[1-9]\d{1,14}$/',
            'residenza' => 'nullable|string|max:255',
            'cittaResidenza' => 'nullable|string|max:255',
            'cap' => 'nullable|string|regex:/^\d{5}$/',
            'azienda' => 'required|boolean',
            'partitaiva' => 'nullable|required_if:azienda,1|string|max:20',
            'codicesdi' => 'nullable|string|max:20',
            'partenza' => 'required|string|max:255',
            'cittaPartenza' => 'required|string|max:255',
            'dataPartenza' => 'required|date_format:Y-m-d H:i:s',
            'arrivo' => 'required|string|max:255',
            'cittaArrivo' => 'required|string|max:255',
            'passeggeri' => 'nullable|integer|min:1',
            'datiPasseggeri' => 'nullable|string|max:10000',
            'bagagli' => 'nullable|integer|min:0',
            'seggiolino' => 'nullable|boolean',
            'codiceEsterno' => 'nullable|string|max:50',
            'infoExtraNoleggio' => 'nullable|string|max:10000',
            'gestionePrenotazione' => 'required|in:personale,condivisa',
        ]);
    
        $gestioneCorrente = $prenotazione->gestionePrenotazione;
        $nuovaGestione = $request->gestionePrenotazione;
    
        // Controllo se la gestione della prenotazione è cambiata da personale a condivisa
        if ($gestioneCorrente === 'personale' && $nuovaGestione === 'condivisa') {
            // Inserire un nuovo record in prenotazioni condivise
            PrenotazioneCondivisa::create([
                'prenotazione_id' => $prenotazione->id,
                'stato' => 'condivisa'
            ]);
        }
        // Controllo se la gestione della prenotazione è cambiata da condivisa a personale
        elseif ($gestioneCorrente === 'condivisa' && $nuovaGestione === 'personale') {
            // Rimuovere il record da prenotazioni condivise se non è accettata
            $condivisa = $prenotazione->condivisioni()->where('stato', '!=', 'accettata')->first();
            if ($condivisa) {
                $condivisa->delete();
            } else {
                return back()->with('error', 'Non è possibile cambiare la gestione da condivisa a personale se la prenotazione è stata già accettata.');
            }
        }
    
        // Aggiorna i dati della prenotazione
        $prenotazione->update($validated);
    
        return redirect()->route('prenotazioni.index-personali')->with('success', 'Prenotazione aggiornata correttamente.');
    }

    /**
     * Remove the specified prenotazione from storage.
     * Rimuove una specifica prenotazione dal database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Prenotazione::findOrFail($id)->delete();
        return redirect()->route('prenotazioni.index-personali')->with('success', 'Prenotazione deleted successfully.');
    }

    /**
     * Fetch accessible prenotazioni based on user's role and permissions.
     * Recupera le prenotazioni accessibili basate sul ruolo e i permessi dell'utente.
     *
     * @param mixed $user User instance
     * @return Collection of Prenotazione
     */
    private function fetchAccessiblePrenotazioni($user)
    {
        // Assumi che l'utente sia collegato a una sola azienda
        $aziendaId = $user->aziende->first()->id;
        
        // Costruisci la query base per le prenotazioni
        $query = Prenotazione::query();

        // Filtra le prenotazioni in base al ruolo dell'utente
        if ($user->permessi->contains('nome', 'Azienda')) {
            $query->where(function ($query) use ($aziendaId) {
                // Prenotazioni personali dell'azienda
                $query->whereHas('utente.aziende', function ($q) use ($aziendaId) {
                    $q->where('id', $aziendaId);
                })->where('gestionePrenotazione', 'personale');

                // Prenotazioni condivise in stato 'condivisa' dall'azienda
                $query->orWhereHas('condivisioni', function ($query) use ($aziendaId) {
                    $query->where('stato', 'condivisa')
                        ->whereHas('prenotazione.utente.aziende', function ($q) use ($aziendaId) {
                            $q->where('id', $aziendaId);
                        });
                });

                // Prenotazioni condivise accettate da un utente della stessa azienda
                $query->orWhereHas('condivisioni', function ($query) use ($aziendaId) {
                    $query->where('stato', 'accettata')
                        ->whereHas('acceptor.aziende', function ($subQuery) use ($aziendaId) {
                            $subQuery->where('id', $aziendaId);
                        });
                });
            });
        } elseif ($user->permessi->contains('nome', 'Autista')) {
            // Per gli autisti, filtra per vedere le proprie prenotazioni personali e quelle condivise
            $query = Prenotazione::query()
                ->where(function ($query) use ($user) {
                    // Prenotazioni personali o condivise inserite dall'utente
                    $query->where('user_id', $user->id)
                        ->where(function ($query) {
                            $query->where('gestionePrenotazione', 'personale')
                                    ->orWhere(function ($query) {
                                        $query->where('gestionePrenotazione', 'condivisa')
                                            ->whereHas('condivisioni', function ($query) {
                                                $query->where('stato', 'condivisa');
                                            });
                                    });
                        });
                    // Prenotazioni condivise accettate dall'utente ma non necessariamente inserite da lui
                    $query->orWhere(function ($query) use ($user) {
                        $query->whereHas('condivisioni', function ($query) use ($user) {
                            $query->where('stato', 'accettata')
                                ->where('acceptor_id', $user->id);
                        });
                    });
                });
        }

        return $query->get();
    }

}

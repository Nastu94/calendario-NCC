<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permesso;
use App\Models\Azienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     * Visualizza un elenco degli utenti.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $users = collect();
    
        if ($user->permessi->contains('nome', 'Amministratore')) {
            // Recupera tutti gli utenti con permesso azienda (id permesso 2)
            $users = User::whereHas('permessi', function ($query) {
                $query->where('id', 2);
            })->get();
            
            return view('admin.index-user', compact('users'));
        } elseif ($user->permessi->contains('nome', 'Azienda')) {
            // Recupera tutti gli autisti (permesso id 3) che appartengono alla stessa azienda dell'utente
            $aziendaId = $user->aziende->first()->id; // Assumendo che 'aziende' sia la relazione definita nel modello User
            $users = User::whereHas('permessi', function ($query) {
                $query->where('id', 3);
            })->whereHas('aziende', function ($query) use ($aziendaId) {
                $query->where('id', $aziendaId);
            })->get();
            
            return view('autisti.index', compact('users'));
        } else {
            // Gestire il caso in cui l'utente non abbia i permessi necessari
            abort(403, 'Accesso non autorizzato');
        }  
    }
    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        
        if ($user->permessi->contains('nome', 'Amministratore')) {
            // Per l'amministratore, mostra tutte le aziende
            $aziende = Azienda::all();
            return view('admin.create-user', compact('aziende'));
        } elseif ($user->permessi->contains('nome', 'Azienda')) {
            $azienda = $user->aziende()->first();
            // Per l'utente azienda, non è necessario passare le aziende perché può gestire solo la propria
            return view('autisti.create', ['azienda_id' => $azienda->id]);
        } else {
            // Gestire il caso in cui l'utente non abbia i permessi necessari
            abort(403, 'Accesso non autorizzato');
        }
    }

    /**
     * Store a newly created user in storage.
     * Salva un nuovo utente nel database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'permesso' => 'required|exists:permessi,id',
            'azienda_id' => 'required|exists:aziende,id'
        ]);
    
        $validatedData['password'] = bcrypt($validatedData['password']);
        $user = User::create($validatedData);
    
        $user->permessi()->attach($request->permesso);
        $user->aziende()->attach($request->azienda_id);
    
        return redirect()->route('dashboard')->with('success', 'Utente creato correttamente.');
    }    

    /**
     * Display the specified user.
     * Visualizza i dettagli di un utente specifico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     * Mostra il form per modificare un utente specifico.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('autisti.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     * Aggiorna i dati di un utente nel database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
        ]);

        // Controlla se la password è stata fornita
        if (!empty($validatedData['password'])) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            // Se la password è vuota, rimuovila dall'array di dati validati
            unset($validatedData['password']);
        }

        User::whereId($id)->update($validatedData);
        
        $user = Auth::user();
        
        if ($user->permessi->contains('nome', 'Azienda')) {
            return redirect()->route('autisti.index')->with('success', 'Autista aggiornato correttamente');
        } elseif ($user->permessi->contains('nome', 'Amministratore')) {
            return redirect()->route('admin.index-azienda')->with('success', 'Utente Azienda aggiornato correttamente');
        }
    }

    /**
     * Remove the specified user from storage.
     * Rimuove un utente dal database.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}

<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AziendaController;
use App\Http\Controllers\VeicoloController;
use App\Http\Controllers\PrenotazioniController;
use App\Http\Controllers\PrenotazioneCondivisaController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\FoglioViaggioController;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckAziendaPermission;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', CheckAdmin::class])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/create-user', [UserController::class, 'create'])->name('admin.create-user');
        Route::post('/store-user', [UserController::class, 'store'])->name('admin.store-user');
        Route::get('/create-azienda', [AziendaController::class, 'create'])->name('admin.create-azienda');
        Route::post('/store-azienda', [AziendaController::class, 'store'])->name('admin.store-azienda');    
    });
});

Route::middleware(['auth', CheckAziendaPermission::class])->group(function () {
    Route::prefix('autisti')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('autisti.index');
        Route::get('/create', [UserController::class, 'create'])->name('autisti.create');
        Route::post('/store', [UserController::class, 'store'])->name('autisti.store');
        Route::get('/edit/{autista}', [UserController::class, 'edit'])->name('autisti.edit');
        Route::put('/update/{autista}', [UserController::class, 'update'])->name('autisti.update');
        Route::delete('/delete/{autista}', [UserController::class, 'destroy'])->name('autisti.delete');
    });
    
    Route::prefix('veicoli')->group(function () {
        Route::get('/', [VeicoloController::class, 'index'])->name('veicoli.index');
        Route::get('/create', [VeicoloController::class, 'create'])->name('veicoli.create');
        Route::post('/store', [VeicoloController::class, 'store'])->name('veicoli.store');
        Route::get('/edit/{veicolo}', [VeicoloController::class, 'edit'])->name('veicoli.edit');
        Route::put('/update/{veicolo}', [VeicoloController::class, 'update'])->name('veicoli.update');
        Route::delete('/delete/{veicolo}', [VeicoloController::class, 'destroy'])->name('veicoli.delete');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('prenotazioni')->group(function () {
        Route::get('/prenotazioni-personali', [PrenotazioniController::class, 'index_personali'])->name('prenotazioni.index-personali');
        Route::get('/prenotazioni-globali', [PrenotazioniController::class, 'index'])->name('prenotazioni.index-globali');
        Route::get('/prenotazioni-aziendali', [PrenotazioniController::class, 'index_aziendali'])->name('prenotazioni.index-aziendali');
        Route::get('/dettaglio/{data}/{tipo}', [PrenotazioniController::class, 'showDailyPrenotazioni'])->name('prenotazioni.dettaglio');
        Route::get('/create', [PrenotazioniController::class, 'create'])->name('prenotazioni.create');
        Route::post('/store', [PrenotazioniController::class, 'store'])->name('prenotazioni.store');
        Route::get('/{prenotazione}/edit', [PrenotazioniController::class, 'edit'])->name('prenotazioni.edit');
        Route::put('/{prenotazione}/update', [PrenotazioniController::class, 'update'])->name('prenotazioni.update');
        Route::delete('/{prenotazione}/delete', [PrenotazioniController::class, 'destroy'])->name('prenotazioni.delete');

        Route::get('/prenotazioni-condivise', [PrenotazioneCondivisaController::class, 'index'])->name('prenotazioni.index-condivise');
        Route::put('/condivise/{prenotazioneCondivisa}/update', [PrenotazioneCondivisaController::class, 'update'])->name('prenotazioni.update-condivise');
    });
    
    Route::get('/registro/{data}', [RegistroController::class, 'index'])->name('registro.index');

    Route::prefix('fogli-viaggio')->group(function () {
        Route::get('/', [FoglioViaggioController::class, 'index'])->name('fogli-viaggio.index');
        Route::get('/create', [FoglioViaggioController::class, 'create'])->name('fogli-viaggio.create');
        Route::post('/store', [FoglioViaggioController::class, 'store'])->name('fogli-viaggio.store');
        Route::get('/{id}', [FoglioViaggioController::class, 'show'])->name('fogli-viaggio.show');
        Route::get('/{id}/edit', [FoglioViaggioController::class, 'edit'])->name('fogli-viaggio.edit');
        Route::put('/{id}/update', [FoglioViaggioController::class, 'update'])->name('fogli-viaggio.update');
        Route::delete('/{id}', [FoglioViaggioController::class, 'destroy'])->name('fogli-viaggio.delete');
    });
});

require __DIR__.'/auth.php';
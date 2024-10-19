<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Questo metodo viene eseguito quando la migrazione viene applicata.
     */
    public function up(): void
    {
        // Crea una nuova tabella 'prenotazioni_condivise' nel database.
        Schema::create('prenotazioni_condivise', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prenotazione_id')->constrained('prenotazioni')->onDelete('cascade');
            $table->foreignId('acceptor_id')->constrained('users')->onDelete('cascade');
            $table->enum('stato', ['condivisa', 'accettata']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * Questo metodo viene eseguito quando la migrazione viene annullata.
     */
    public function down(): void
    {
        // Rimuove la tabella 'prenotazioni_condivise' dal database.
        Schema::dropIfExists('prenotazioni_condivise');
    }
};

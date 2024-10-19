<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prenotazioni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nome');
            $table->string('cognome');
            $table->string('email')->nullable();
            $table->string('cellulare')->nullable();
            $table->string('residenza')->nullable();
            $table->string('cittaResidenza')->nullable();
            $table->string('cap')->nullable();
            $table->boolean('azienda')->comment('true se azienda, false se no');
            $table->string('partitaiva')->nullable();
            $table->string('codicesdi')->nullable();
            $table->string('partenza');
            $table->string('cittaPartenza');
            $table->dateTime('dataPartenza');
            $table->string('arrivo');
            $table->string('cittaArrivo');
            $table->integer('passeggeri')->nullable();
            $table->text('datiPasseggeri')->nullable();
            $table->integer('bagagli')->nullable();
            $table->boolean('seggiolino')->comment('true se si, false se no')->nullable();
            $table->string('codiceEsterno')->unique()->nullable();
            $table->text('infoExtraNoleggio')->nullable();
            $table->text('gestionePrenotazione');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prenotazioni');
    }
};


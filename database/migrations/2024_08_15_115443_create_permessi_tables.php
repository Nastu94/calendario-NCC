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
        Schema::create('permessi', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
        });
        
        // Inserisci i ruoli
        DB::table('permessi')->insert([
            ['nome' => 'Amministratore'],
            ['nome' => 'Azienda'],
            ['nome' => 'Autista'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permessi');
    }
};

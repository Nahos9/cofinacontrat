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
        Schema::create('attestations', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            // $table->string('last_name')->nullable();
            $table->string('civilite')->enum(['Monsieur', 'Madame'])->nullable();
            $table->string('raison_sociale')->nullable();
            // $table->string('email')->nullable();
            $table->string('phone')->nullable();
            // $table->string('address')->nullable();
            $table->string('montant_endettement')->nullable();
            $table->string('city')->nullable();
            $table->string('account_number')->nullable();
            $table->string('type')->enum(['personne physique', 'personne morale'])->nullable();
            $table->date('date_de_creation_compte')->nullable();
            $table->string('type_attestation')->nullable();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attestations');
    }
};

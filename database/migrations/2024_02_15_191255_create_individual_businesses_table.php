<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('individual_businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId("contract_id")->constrained(table: "contracts", column: "id")->cascadeOnDelete();
            $table->string("denomination");
            $table->string("head_office_address");
            $table->string("rccm_number");
            $table->string("phone_number");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individual_businesses');
    }
};

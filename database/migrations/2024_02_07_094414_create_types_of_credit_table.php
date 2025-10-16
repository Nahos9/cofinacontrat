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
        Schema::create('types_of_credit', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('full_name')->nullable();
            $table->integer('min_month');
            $table->integer('max_month');
            $table->foreignId('type_of_applicant_id')->constrained(table: 'types_of_applicant', column: 'id')->cascadeOnDelete();
            $table->unique(["name", "min_month", "max_month"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('types_of_credit');
    }
};

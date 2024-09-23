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
		Schema::create('guarantees', function (Blueprint $table) {
			$table->id();
			$table->foreignId('verbal_trial_id')->constrained(table: 'verbals_trials', column: 'id')->cascadeOnDelete();
			$table->foreignId('type_of_guarantee_id')->constrained(table: 'types_of_guarantee', column: 'id')->cascadeOnDelete();
			$table->text('comment')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('guarantees');
	}
};

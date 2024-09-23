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
		Schema::create('deadline_postponed_files', function (Blueprint $table) {
			$table->id();
			$table->string("title");
			$table->foreignId("deadline_postponed_id")->constrained()->cascadeOnDelete();
			$table->string("path");
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('deadline_postponed_files');
	}
};

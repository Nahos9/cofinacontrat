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
		Schema::create('deadline_postponeds', function (Blueprint $table) {
			$table->id();
			$table->foreignId("caf_id")->constrained(table: "users", column: "id")->cascadeOnDelete();
			$table->string("credit_number");
			$table->integer("deadline_number");
			$table->date("old_date");
			$table->date("new_date");
			$table->string("request_path");
			$table->string("memo_path");
			$table->enum("status", ["waiting_ca", "rejected_by_ca", "waiting_dex", "rejected_by_dex", "waiting_head", "rejected_by_head", "waiting_md", "rejected_by_md", "waiting_credit_admin", "rejected_by_credit_admin", "waiting_report", "reported"])->default("waiting_ca");
			$table->text("comment")->nullable();
			$table->integer("extension");
			$table->string("beneficiary_label");
			$table->decimal('loan_amount', 21, 2);
			$table->enum("representative_civility", ["Mr", "Mme", "Mlle"]);
			$table->string("representative_last_name");
			$table->string("representative_first_name");
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('deadline_postponeds');
	}
};

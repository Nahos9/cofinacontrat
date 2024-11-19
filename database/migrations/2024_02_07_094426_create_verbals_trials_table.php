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
		Schema::create('verbals_trials', function (Blueprint $table) {
			$table->id();
			$table->string("committee_id")->nullable();
			$table->date("committee_date");
			$table->enum("civility", ["Mr", "Mme", "Mlle"]);
			$table->string("applicant_first_name");
			$table->string("applicant_last_name");
			$table->string("account_number");
			$table->string("activity");
			$table->string("purpose_of_financing");
			$table->foreignId('type_of_credit_id')->constrained(table: 'types_of_credit', column: 'id')->cascadeOnDelete();
			$table->decimal('amount', 21, 2);
			$table->integer('duration');
			$table->enum('periodicity', ['mensual', 'quarterly', "semi-annual", "annual", 'in-fine']);
			$table->float('taf')->nullable();
			$table->decimal('due_amount', 21, 2);
			$table->decimal('administrative_fees_percentage', 21, 2);
			$table->decimal('insurance_premium', 21, 2);
			$table->float('tax_fee_interest_rate');
			$table->foreignId('caf_id')->constrained(table: "users", column: "id")->cascadeOnDelete();
			$table->foreignId('credit_admin_id')->constrained(table: "users", column: "id")->cascadeOnDelete();
			$table->foreignId("creator_id")->constrained(table: "users", column: "id")->cascadeOnDelete();
			$table->enum("validation_level", ["credit_admin", "head_credit", "md"])->default('credit_admin');
			$table->enum('status', ["waiting", 'rejected', "validated"])->default('waiting');
			$table->string("comment")->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('verbals_trials');
	}
};

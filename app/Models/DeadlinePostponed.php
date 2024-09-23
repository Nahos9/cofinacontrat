<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeadlinePostponed extends Model
{
	use HasFactory;

	protected $fillable = [
		"caf_id",
		"credit_number",
		"deadline_number",
		"new_date",
		"request_path",
		"old_date",
		"memo_path",
		"status",
		"comment",
		"extension",
		"beneficiary_label",
		"loan_amount",
		"representative_civility",
		"representative_last_name",
		"representative_first_name",
	];

	public function toArray()
	{
		$data = parent::toArray();
		$data["created_at_fr"] = Carbon::parse($data["created_at"])->format("d/m/Y H:i:s");
		$data["updated_at_fr"] = Carbon::parse($data["updated_at"])->format("d/m/Y H:i:s");
		$data["caf_id"] = isset($data["caf_id"]) ? (int) $data["caf_id"] : null;
		$data["deadline_number"] = isset($data["deadline_number"]) ? (int) $data["deadline_number"] : null;
		$data["extension"] = isset($data["extension"]) ? (int) $data["extension"] : null;
		$data["status_fr"] = [
			"waiting_ca" => ["color" => "warning", "value" => "En attente du CA"],
			"rejected_by_ca" => ["color" => "error", "value" => "Rejeté par CA"],
			"waiting_dex" => ["color" => "warning", "value" => "En attente de DEX"],
			"rejected_by_dex" => ["color" => "error", "value" => "Rejeté par DEX"],
			"waiting_head" => ["color" => "warning", "value" => "En attente de HEAD"],
			"rejected_by_head" => ["color" => "error", "value" => "Rejeté par HEAD"],
			"waiting_md" => ["color" => "warning", "value" => "En attente de MD"],
			"rejected_by_md" => ["color" => "error", "value" => "Rejeté par MD"],
			"waiting_credit_admin" => ["color" => "warning", "value" => "En attente de ADMIN CREDIT"],
			"rejected_by_credit_admin" => ["color" => "error", "value" => "Rejeté par ADMIN CREDIT"],
			"waiting_report" => ["color" => "warning", "value" => "En attente du OPERATION"],
			"reported" => ["color" => "success", "value" => "Reporté"],
		][$data["status"]];
		return $data;
	}

	public function caf(): BelongsTo
	{
		return $this->belongsTo(User::class, "caf_id", "id");
	}
}

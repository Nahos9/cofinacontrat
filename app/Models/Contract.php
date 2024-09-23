<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Contract extends Model
{
	use HasFactory;

	protected $fillable = [
		"verbal_trial_id",
		"representative_birth_date", //
		"representative_birth_place", //
		"representative_nationality", //
		"representative_home_address",
		"representative_phone_number", //
		"representative_type_of_identity_document",
		"representative_number_of_identity_document", //
		"representative_office_delivery",
		"representative_date_of_issue_of_identity_document",
		"risk_premium_percentage",
		"total_amount_of_interest",
		"number_of_due_dates",
		'type',
		'has_pledges',
		'creator_id',
		'signed_contract_path',
		'signed_promissory_note_path',
		'date_of_first_echeance',
		'date_of_last_echeance',
		'due_amount',
		'status',
		'status_observation',
	];

	// protected $with = ['company', 'individual_business'];
	protected $appends = ['guarantors_count', 'observations', 'upload_completed'];


	public function toArray()
	{
		$data = parent::toArray();
		$data["created_at_fr"] = Carbon::parse($data["created_at"])->format("d/m/Y H:i:s");
		$data["updated_at_fr"] = Carbon::parse($data["updated_at"])->format("d/m/Y H:i:s");
		$data["verbal_trial_id"] = (int) $data["verbal_trial_id"];
		$data["has_pledges"] = (string) $data["has_pledges"];
		$data["representative_birth_date_fr"] = Carbon::parse($data["representative_birth_date"])->format("d/m/Y");
		$data["representative_birth_date_fr"] = Carbon::parse($data["representative_birth_date"])->format("d/m/Y");
		$data["representative_date_of_issue_of_identity_document_fr"] = Carbon::parse($data["representative_date_of_issue_of_identity_document"])->format("d/m/Y");
		return $data;
	}


	public function verbal_trial(): BelongsTo
	{
		return $this->belongsTo(VerbalTrial::class, 'verbal_trial_id', 'id');
	}

	public function guarantors(): HasMany
	{
		return $this->hasMany(Guarantor::class, "contract_id", "id");
	}

	public function company(): HasOne
	{
		return $this->hasOne(Company::class, "contract_id", "id");
	}
	public function individual_business(): HasOne
	{
		return $this->hasOne(IndividualBusiness::class, "contract_id", "id");
	}

	public function pledges(): HasMany
	{
		return $this->hasMany(Pledge::class, "contract_id", "id");
	}

	public function creator(): BelongsTo
	{
		return $this->belongsTo(User::class, "creator_id", "id");
	}

	public function c_a_t(): HasOne
	{
		return $this->hasOne(CAT::class, "contract_id", "id");
	}

	public function getGuarantorsCountAttribute()
	{
		return count($this->guarantors);
	}

	public function getObservationsAttribute()
	{
		$observations = [];
		if (!$this->signed_contract_path)
			$observations[] = "Contrat signé manquant";
		// if (!$this->signed_promissory_note_path)
		// 	$observations[] = "Billet à ordre signé manquant";
		// $incompleteGuarantor = $this->guarantors->filter(function ($item) {
		// 	return $item['signed_contract_path'] == null || $item['signed_promissory_note_path'] == null;
		// })->toArray();
		// if ($incompleteGuarantor)
		// 	$observations[] = "Dossier des cautions incomplet";
		return $observations;
	}

	public function getUploadCompletedAttribute()
	{
		return empty($this->observations);
	}
}



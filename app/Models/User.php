<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens, HasFactory, Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $connection = 'mysql';
	protected $fillable = [
		"name",
		"email",
		"full_name",
		"profile",
		"email_verified_at",
		"password",
		"si_profile_id",
		"activated",
		"password_change_required",
		"signatory_path"
	];

	protected $appends = ['ability_rules', 'profile_fr'];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
		'password' => 'hashed',
	];

	public function toArray()
	{
		$data = parent::toArray();
		$data["created_at_fr"] = Carbon::parse($data["created_at"])->format("d/m/Y H:i:s");
		$data["updated_at_fr"] = Carbon::parse($data["updated_at"])->format("d/m/Y H:i:s");
		$data["email_verified_at_fr"] = Carbon::parse($data["email_verified_at"])->format("d/m/Y H:i:s");
		// $data["signatory_path"] = "/storage" . $data["signatory_path"];
		return $data;
	}

	public function verbal_trial(): HasMany
	{
		return $this->hasMany(VerbalTrial::class, 'caf_id', "id");
	}

	public function verbals_trials(): HasMany
	{
		return $this->hasMany(VerbalTrial::class, "creator_id", "id");
	}
	public function contracts(): HasMany
	{
		return $this->hasMany(Contract::class, "creator_id", "id");
	}
	public function notifications(): HasMany
	{
		return $this->hasMany(Notification::class, "creator_id", "id");
	}

	public function deadline_postponeds(): HasMany
	{
		return $this->hasMany(DeadlinePostponed::class, "caf_id", "id");
	}

	public function getProfileFrAttribute()
	{
		return [
			"admin" => "Administrateur",
			"credit_analyst" => "Analyste Crédit",
			"credit_admin" => "Admin Crédit",
			"head_credit" => "Head Crédit",
			"operation" => "Opérations",
			"legal" => "Juridique",
			"dex" => "DEX",
			"caf" => "CAF",
			"ca" => "Chef d'agence",
			"md" => "MD",
		][$this->profile];
	}

	public function getAbilityRulesAttribute()
	{
		switch ($this->profile) {
			case ('admin'):
				return [
					[
						'action' => ['manage'],
						'subject' => ['all'],
					]
				];
			case ('credit_analyst'):
				return [
					[
						"action" => "read",
						"subject" => ["non-mortgage-contract", "mortgage-contract"]
					],
					[
						"action" => ["read", "historical", "create", "update", "delete", "download"],
						"subject" => ["pv"],
					],
					[
						"action" => ["read_caf"],
						"subject" => ["user"]
					],
					[
						"action" => ["read"],
						"subject" => ["type-of-guarantee", "type-of-credit", "type-of-applicant"]
					],
					[
						"action" => ["manage"],
						"subject" => ["settings-user"]
					]
				];
			case ('credit_admin'):
				return [
					[
						"action" => "read",
						"subject" => ["non-mortgage-contract", "mortgage-contract"]
					],
					[
						"action" => ["read", "historical", "download", "change_status", "validate", "reject","upload"],
						"subject" => ["pv"],
					],
					[
						"action" => ["create", "read", "historical", "update", "change_status", "reject", "validate", "delete", "download","upload"],
						"subject" => ["contract"],
					],
					[
						"action" => ["create", "read", "without-signed-contract", "historical", "update", "delete", "download", "change_status"],
						"subject" => ["notification"],
					],
					[
						"action" => ["create", "simple-notification", "read", "historical", "without-signed-notification", "update", "delete", "download"],
						"subject" => ["simple-notification"],
					],
					[
						"action" => ["create", "read", "historical", "delete", "download", "change_stsatus"],
						"subject" => ["cat-simple-notification"]
					],
					[
						"action" => ["create", "read", "update", "delete", "download"],
						"subject" => ["guarantor"],
					],
					[
						"action" => ["read", "create", "update", "delete", "download"],
						"subject" => ["cat"],
					],
					[
						"action" => ["read"],
						"subject" => ["type-of-guarantee", "type-of-credit", "type-of-applicant"]
					],
					[
						"action" => ["read", "historical", "change_status", "download"],
						"subject" => ["deadline-postponed"],
					],
					[
						"action" => ["manage"],
						"subject" => ["settings-user"]
					]
				];
			case ('head_credit'):
				return [
					[
						"action" => "read",
						"subject" => ["non-mortgage-contract", "mortgage-contract"]
					],
					[
						"action" => ["read", "historical", "download", "reject", "validate", "change_status"],
						"subject" => ["pv"],
					],
					[
						"action" => ["read", "historical", "download"],
						"subject" => ["contract"],
					],
					[
						"action" => ["read", "without-signed-contract", "historical", "download", "validate", "reject", "change_head_credit_status"],
						"subject" => ["notification"],
					],
					[
						"action" => ["read", "simple-notification", "without-signed-notification", "historical", "download", "validate", "reject", "change_head_credit_status"],
						"subject" => ["simple-notification"],
					],
					[
						"action" => ["read", "download"],
						"subject" => ["guarantor"],
					],
					[
						"action" => ["read", "download", "validate", "reject_validation", "download"],
						"subject" => ["cat"],
					],
					[
						"action" => ["read"],
						"subject" => ["type-of-guarantee", "type-of-credit", "type-of-applicant"]
					],
					[
						"action" => ["read", "historical", "change_status", "download"],
						"subject" => ["deadline-postponed"],
					],
					[
						"action" => ["manage"],
						"subject" => ["settings-user"]
					]
				];
			case ('operation'):
				return [
					[
						"action" => ["read"],
						"subject" => ["non-mortgage-contract", "mortgage-contract", "simple-notification"]
					],
					[
						"action" => ["simple-notification"],
						"subject" => ["simple-notification"]
					],
					[
						"action" => ["read", "download", "unblock", "reject_unblock"],
						"subject" => ["cat"],
					],
					[
						"action" => ["read"],
						"subject" => ["type-of-guarantee", "type-of-credit", "type-of-applicant"]
					],
					[
						"action" => ["read", "historical", "change_status", "download"],
						"subject" => ["deadline-postponed"],
					],
					[
						"action" => ["manage"],
						"subject" => ["settings-user"]
					]
				];
			case ('legal'):
				return [
					[
						"action" => ["read"],
						"subject" => ["mortgage-contract"]
					],
					[
						"action" => ["without-signed-contract", "upload_signed_notification"],
						"subject" => ["notification"]
					],
					[
						"action" => ["manage"],
						"subject" => ["settings-user"]
					]
				];
			case ('dex'):
				return [
					[
						"action" => ["simple-notification", "read", "historical", "download"],
						"subject" => ["contract", "cat", "guarantor", "non-mortgage-contract", "mortgage-contract", "notification", "simple-notification"]
					],
					[
						"action" => ["read", "historical", "download", "reject", "validate", "change_status"],
						"subject" => ["pv"],
					],
					[
						"action" => ["without-signed-contract"],
						"subject" => ["notification"]
					],
					[
						"action" => ["without-signed-notification"],
						"subject" => ["simple-notification"]
					],
					[
						"action" => ["read"],
						"subject" => ["type-of-guarantee", "type-of-credit", "type-of-applicant"]
					],
					[
						"action" => ["read", "historical", "change_status", "download"],
						"subject" => ["deadline-postponed"],
					],
					[
						"action" => ["manage"],
						"subject" => ["settings-user"]
					]
				];
			case ('caf'):
				return [
					[
						"action" => "read",
						"subject" => ["non-mortgage-contract", "mortgage-contract"]
					],
					[
						"action" => ["read", "upload", "download", "send"],
						"subject" => ["contract", "guarantor"],
					],
					[
						"action" => ["without-signed-contract", "upload", "download", "send"],
						"subject" => ["notification"]
					],
					[
						"action" => ["simple-notification", "without-signed-notification", "upload", "download", "send"],
						"subject" => ["simple-notification"]
					],
					[
						"action" => ["read"],
						"subject" => ["type-of-guarantee", "type-of-credit", "type-of-applicant"]
					],
					[
						"action" => ["read", "historical", "create", "update", "download", "upload", "delete"],
						"subject" => ["deadline-postponed"],
					],
					[
						"action" => ["manage"],
						"subject" => ["settings-user"]
					]
				];
			case ('ca'):
				return [
					[
						"action" => ["read", "historical", "change_status", "download"],
						"subject" => ["deadline-postponed"],
					],
					[
						"action" => ["manage"],
						"subject" => ["settings-user"]
					]
				];
			case ('md'):
				return [
					[
						"action" => "read",
						"subject" => ["non-mortgage-contract", "mortgage-contract"]
					],
					[
						"action" => ["read", "historical", "download", "reject", "validate", "change_status"],
						"subject" => ["pv"],
					],
					[
						"action" => ["read", "historical", "download"],
						"subject" => ["contract"],
					],
					[
						"action" => ["read", "without-signed-contract", "historical", "download", "validate", "reject", "change_head_credit_status"],
						"subject" => ["notification"],
					],
					[
						"action" => ["read", "simple-notification", "without-signed-notification", "historical", "download", "validate", "reject", "change_head_credit_status"],
						"subject" => ["simple-notification"],
					],
					[
						"action" => ["read", "download"],
						"subject" => ["guarantor"],
					],
					[
						"action" => ["read", "download", "validate", "reject_validation", "download"],
						"subject" => ["cat"],
					],
					[
						"action" => ["read"],
						"subject" => ["type-of-guarantee", "type-of-credit", "type-of-applicant"]
					],
					[
						"action" => ["read", "historical", "change_status", "download"],
						"subject" => ["deadline-postponed"],
					],
					[
						"action" => ["manage"],
						"subject" => ["settings-user"]
					]
				];
		}
	}
}

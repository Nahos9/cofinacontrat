<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guarantor extends Model
{
    use HasFactory;

    protected $table = "guarantors";

    protected $fillable = [
        "contract_id",
        "notification_id",
        "civility",
        "first_name",
        "last_name",
        "birth_date",
        "birth_place",
        "nationality",
        "home_address",
        "type_of_identity_document",
        "number_of_identity_document",
        "date_of_issue_of_identity_document",
        "function",
        "phone_number",
        'signed_contract_path',
        'signed_promissory_note_path',
    ];

    protected $appends = ["full_name", "observations"];

    public function toArray()
    {
        $data = parent::toArray();
        $data["created_at_fr"] = Carbon::parse($data["created_at"])->format("d/m/Y H:i:s");
        $data["updated_at_fr"] = Carbon::parse($data["updated_at"])->format("d/m/Y H:i:s");
        $data["birth_date_fr"] = Carbon::parse($data["birth_date"])->format("d/m/Y");
        $data["contract_id"] = isset($data["contract_id"]) ? (int) $data["contract_id"] : null;
        $data["notification_id"] = isset($data["notification_id"]) ? (int) $data["notification_id"] : null;
        $data["date_of_issue_of_identity_document_fr"] = Carbon::parse($data["date_of_issue_of_identity_document"])->format("d/m/Y");
        return $data;
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, "contract_id", "id");
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public function getObservationsAttribute()
    {
        $observations = [];
        if (!$this->signed_contract_path && $this->contract_id)
            $observations[] = "Contrat signé manquant";
        if (!$this->signed_promissory_note_path)
            $observations[] = "Billet à ordre signé manquant";

        return $observations;
    }

    public function notification(): BelongsTo
    {
        return $this->belongsTo(Notification::class, "notification_id", "id");
    }
}

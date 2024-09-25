<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VerbalTrial extends Model
{
    use HasFactory;

    protected $table = "verbals_trials";

    protected $fillable = [
        "committee_id",
        "committee_date",
        'civility',
        'applicant_first_name',
        'applicant_last_name',
        'account_number',
        'activity',
        'purpose_of_financing',
        'type_of_credit_id',
        'amount',
        'duration',
        'periodicity',
        // 'taf',
        'due_amount',
        'administrative_fees_percentage',
        'insurance_premium',
        'tax_fee_interest_rate',
        'credit_admin_id',
        'caf_id',
        'creator_id',
        'validation_level',
        'status',
        'comment',
    ];

    protected $appends = ["applicant_full_name", "label", "amount_fr"];

    public function toArray()
    {
        $data = parent::toArray();
        $data["created_at_fr"] = Carbon::parse($data["created_at"])->format('d/m/Y H:i:s');
        $data["updated_at_fr"] = Carbon::parse($data["updated_at"])->format('d/m/Y H:i:s');
        $data["type_of_credit_id"] = (int) $data["type_of_credit_id"];
        $data["amount"] = (float) $data["amount"];
        // $data["taf"] = (float) $data["taf"];
        $data["due_amount"] = (float) $data["due_amount"];
        $data["administrative_fees_percentage"] = (float) $data["administrative_fees_percentage"];
        $data["insurance_premium"] = (float) $data["insurance_premium"];
        $data["tax_fee_interest_rate"] = (float) $data["tax_fee_interest_rate"];
        $data["caf_id"] = (int) $data["caf_id"];
        return $data;
    }

    public function type_of_credit(): BelongsTo
    {
        return $this->belongsTo(TypeOfCredit::class, 'type_of_credit_id', 'id');
    }

    public function guarantees(): HasMany
    {
        return $this->hasMany(Guarantee::class, "verbal_trial_id", "id");
    }

    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class, 'verbal_trial_id', 'id');
    }

    public function pep(): HasOne
    {
        return $this->hasOne(Pep::class, 'verbal_trial_id', 'id');
    }
    public function caf(): BelongsTo
    {
        return $this->belongsTo(User::class, 'caf_id', "id");
    }
    public function credit_admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'credit_admin_id', "id");
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, "creator_id", "id");
    }

    public function getApplicantFullNameAttribute()
    {
        return $this->applicant_first_name . " " . $this->applicant_last_name;
    }

    public function getLabelAttribute()
    {
        return "$this->applicant_full_name($this->committee_id)";
    }

    public function getAmountFrAttribute()
    {
        return number_format(((float) $this["amount"]), 0, ',', ' ') . " FCFA";
    }

    public function notification(): HasOne
    {
        return $this->hasOne(Notification::class, 'verbal_trial_id', 'id');
    }
}

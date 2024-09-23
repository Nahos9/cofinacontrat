<?php

namespace App\Models;

use App\Models\TypeOfApplicant;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeOfCredit extends Model
{
    use HasFactory;

    protected $table = "types_of_credit";

    protected $fillable = [
        "name",
        "type_of_applicant_id",
        "min_month",
        "max_month",
    ];

    protected $appends = ["full_name"];
    public function toArray()
    {
        $data = parent::toArray();
        $data["created_at_fr"] = Carbon::parse($data["created_at"])->format("d/m/Y H:i:s");
        $data["updated_at_fr"] = Carbon::parse($data["updated_at"])->format("d/m/Y H:i:s");
        return $data;
    }

    public function type_of_applicant(): BelongsTo
    {
        return $this->belongsTo(TypeOfApplicant::class, "type_of_applicant_id", "id");
    }

    public function verbals_trials(): HasMany
    {
        return $this->hasMany(VerbalTrial::class, 'type_of_credit_id', 'id');
    }

    public function getFullNameAttribute()
    {
        return $this->name . " " . $this->min_month . " à " . $this->max_month . " mois";
    }
}

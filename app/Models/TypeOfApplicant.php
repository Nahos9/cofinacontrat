<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeOfApplicant extends Model
{
    use HasFactory;

    protected $table = "types_of_applicant";

    protected $fillable = [
        "name",
    ];

    public function toArray()
    {
        $data = parent::toArray();
        $data["created_at_fr"] = Carbon::parse($data["created_at"])->format("d/m/Y H:i:s");
        $data["updated_at_fr"] = Carbon::parse($data["updated_at"])->format("d/m/Y H:i:s");
        return $data;
    }

    public function types_of_credit(): HasMany
    {
        return $this->hasMany(TypeOfCredit::class, "type_of_applicant_id", "id");
    }

    public function verbals_trials(): HasMany
    {
        return $this->hasMany(VerbalTrial::class, 'type_of_applicant_id', 'id');
    }
}

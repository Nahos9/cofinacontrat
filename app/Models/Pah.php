<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pah extends Model
{
    use HasFactory;
    protected $fillable = [
        "verbal_trial_id",
        "type_of_guarantee_id",
        "montant_terrain",
        "commune",
        "adresse",
        "superficie",
    ];

    public function verbal_trial(): BelongsTo
    {
        return $this->belongsTo(VerbalTrial::class, "verbal_trial_id", "id");
    }

    public function type_of_guarantee(): BelongsTo
    {
        return $this->belongsTo(TypeOfGuarantee::class, "type_of_guarantee_id", "id");
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pep extends Model
{
    use HasFactory;
    protected $table = "pep";

    protected $fillable = [
        "verbal_trial_id",
        "type_of_guarantee_id",
        "montant",
        "date_debut",
        "taux_annuel",
        "duree",
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

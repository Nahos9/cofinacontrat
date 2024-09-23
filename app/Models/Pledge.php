<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pledge extends Model
{
    use HasFactory;

    protected $table = "pledges";

    protected $fillable = [
        "contract_id",
        "type",
        "comment",
        "montant_estime",
        "marque",
        "date_mise_en_circulation",
        "date_carte_crise",
        "immatriculation",
        "numero_serie",
        "model",
        "genre",

    ];

    public function toArray()
    {
        $data = parent::toArray();
        $data["created_at_fr"] = Carbon::parse($data["created_at"])->format("d/m/Y H:i:s");
        $data["updated_at_fr"] = Carbon::parse($data["updated_at"])->format("d/m/Y H:i:s");
        return $data;
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, "contract_id", "id");
    }
}

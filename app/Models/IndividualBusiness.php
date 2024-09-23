<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndividualBusiness extends Model
{
    use HasFactory;

    protected $table = "individual_businesses";

    protected $fillable = [
        "contract_id",
        "denomination",
        "corporate_purpose",
        "head_office_address",
        "rccm_number",
        "nif",
        "phone_number",
        "date_naiss",
        "date_delivrance",
        "lieux_naiss",
        "office_delivery",
        "home_address",
        "num_piece",
        "first_name",
        "last_name",
        "nationalite",
        "number_phone",
        "commune",
        "bp",
        "civility",
        "type_of_identity_document",
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

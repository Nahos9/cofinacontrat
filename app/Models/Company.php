<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    use HasFactory;

    protected $table = "companies";

    protected $fillable = [
        "contract_id",
        "denomination",
        "legal_status",
        "head_office_address",
        "rccm_number",
        "phone_number"
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

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeOfGuarantee extends Model
{
    use HasFactory;

    protected $table = "types_of_guarantee";

    protected $fillable = [
        "name",
    ];

    public function toArray()
    {
        $data = parent::toArray();
        $data["created_at_fr"] = Carbon::parse($data["created_at"])->format("d/m/Y H:i:s");
        $data["updated_at_fr"] = Carbon::parse($data["updated_at"])->format("d/m/Y H:i:s");
        $data["id"] = (int) ($data["id"]);
        return $data;
    }

    public function guarantee(): HasMany
    {
        return $this->hasMany(Guarantee::class, 'type_of_guarantee_id', 'id');
    }
}

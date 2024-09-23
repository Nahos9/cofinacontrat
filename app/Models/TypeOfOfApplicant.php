<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOfOfApplicant extends Model
{
    use HasFactory;
    public function toArray()
    {
        $data = parent::toArray();
        $data["created_at_fr"] = Carbon::parse($data["created_at"])->format("d/m/Y H:i:s");
        $data["updated_at_fr"] = Carbon::parse($data["updated_at"])->format("d/m/Y H:i:s");
        return $data;
    }
}

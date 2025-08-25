<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attestation_gage extends Model
{
    use HasFactory;
    protected $fillable = [
        'immatriculation',
        'marque',
    ];
    public function attestation()
    {
        return $this->belongsTo(attestation::class, 'attestation_id', 'id');
    }
}

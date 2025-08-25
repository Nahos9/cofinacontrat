<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attestation extends Model
{
    use HasFactory;
    protected $fillable = [
        'last_name',
        'civilite',
        'first_name',
        'raison_sociale',
        'email',
        'phone',
        'address',
        'montant_endettement',
        'city',
        'type',
        'account_number',
        'date_de_creation_compte',
        'type_attestation',
        'is_deleted'
    ];

    public function gages()
    {
        return $this->hasMany(attestation_gage::class, 'attestation_id', 'id'   );
    }
}

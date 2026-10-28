<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $guarded = ['id'];

    protected $attributes = ['provider' => 'kkiapay'];

    protected $casts = ['confirmed_at' => 'datetime', 'amount_centimes' => 'integer', 'amount_xof' => 'integer'];

    public function projet()
    {
        // Conserver la destination historique même après retrait du projet.
        return $this->belongsTo(Projet::class)->withTrashed();
    }
}

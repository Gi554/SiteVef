<?php

namespace App\Models;

class Donation extends \Illuminate\Database\Eloquent\Model
{
    protected $guarded = ['id'];

    protected $attributes = ['provider' => 'kkiapay'];

    protected $casts = ['confirmed_at' => 'datetime', 'amount_centimes' => 'integer', 'amount_xof' => 'integer'];

    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }
}

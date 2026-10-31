<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Projet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'titre',
        'description',
        'description_longue',
        'image', 'slug', 'statut', 'publication', 'localisation', 'contexte', 'solution', 'objectifs', 'beneficiaires', 'equipe', 'financement_note', 'indicateurs', 'etapes', 'depenses', 'faq', 'montants_suggeres', 'budget_centimes', 'dons_actifs', 'seo_title', 'meta_description',
    ];

    protected $casts = ['indicateurs' => 'array', 'etapes' => 'array', 'depenses' => 'array', 'faq' => 'array', 'montants_suggeres' => 'array', 'dons_actifs' => 'boolean', 'budget_centimes' => 'integer'];

    public function scopePublished($query)
    {
        return $query->where('publication', 'publie');
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function getCollectedAttribute(): int
    {
        return (int) $this->donations()->where('status', 'confirmed')->sum('amount_centimes');
    }

    public function images()
    {
        return $this->hasMany(ProjetImage::class)->orderBy('ordre');
    }

    public function getGalerieAttribute(): array
    {
        if ($this->images->isNotEmpty()) {
            return $this->images->map(fn ($img) => asset($img->chemin))->values()->all();
        }

        return $this->image ? [asset($this->image)] : [];
    }
}

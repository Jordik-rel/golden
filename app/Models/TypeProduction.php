<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TypeProduction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'libelle_type_production'
    ];

    /**
     * A un type de production, on associe un ou plusieurs mouvement de stock
     */
    public function mouvements():HasMany
    {
        return $this->hasMany(MouvementStock::class);
    }

    /**
     * A un type de production,  on associe une ou plusieures production journaliere
     */
    public function productions():HasMany
    {
        return $this->hasMany(ProductionJournaliere::class);
    }
}

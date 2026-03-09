<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatierePremiere extends Model
{
    use SoftDeletes;

    protected $fillable = [ 
        'libelle_matiere',
        'unite',
        'seuil_min',
        'seuil_alerte',
        'quantite'
    ];

    /**
     * Une matiere, appartient un ou plussieurs mouvement de stock
     */
    public function mouvements():HasMany
    {
        return $this->hasMany(MouvementStock::class);
    }

    /**
     * Une matiere , appartient un ou plusieurs details d'inventaire
     */
    public function details():HasMany
    {
        return $this->hasMany(DetailInventaire::class);
    }
}

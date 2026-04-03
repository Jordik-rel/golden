<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MouvementStock extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'matiere_premiere_id',
        'user_id',
        'type_production_id',
        'type_mouvement',
        'libelle_mouvement',
        'fournisseur_id',
        'quantite'
    ];

    /**
     * A un mouvement de stock, on associe une matiere
     */
    public function matiere():BelongsTo
    {
        return $this->belongsTo(MatierePremiere::class,'matiere_premiere_id');
    }

    /**
     * A un mouvement de stocck , on associe un utilisateur
     */
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A un mouvement de stock, on associe un type de production
     */
    public function type():BelongsTo
    {
        return $this->belongsTo(TypeProduction::class, 'type_production_id');
    }

    /**
     * A un mouvement de stock, on associe un fournisseur
     */
    public function fournisseur():BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }
}

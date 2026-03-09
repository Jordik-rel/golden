<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventaire extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'libelle_inventaire',
        'user_id',
        'date_debut',
        'date_fin',
        'avec_correction_stock',
        'etat',
    ];


    /**
     * A un inventaire , on associe un et un seul utilisateur
     */
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A un inventaire, on associe un ou plusieurs details d'inventaire
     */
    public function details():HasMany
    {
        return $this->hasMany(DetailInventaire::class);
    }
}

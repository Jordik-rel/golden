<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailInventaire extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'inventaire_id',
        'user_id',
        'matiere_premiere_id',
        'stock_theorique',
        'stock_reel',
        'ecart'
    ];

    /**
     * A un detail d'inventaire , n associe qu'un inventaire
     */
    public function inventaire():BelongsTo
    {
        return $this->belongsTo(Inventaire::class);
    }

    /**
     * A un détail d'inventaire, on associe un et un seul utilisateur
     */
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A un detail d'inventaire, on associe une et une seule matiere
     */
    public function matiere():BelongsTo
    {
        return $this->belongsTo(MatierePremiere::class);
    }
}

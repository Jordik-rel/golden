<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionJournaliere extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'date_production',
        'type_production_id',
        'quantite',
    ];

    /**
     * A une production journaliere , on associe un et un seul utilisateur
     */
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function type():BelongsTo
    {
        return $this->belongsTo(TypeProduction::class,'type_production_id');
    }
}

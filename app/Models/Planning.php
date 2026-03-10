<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Planning extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'jour_travail',
        'user_id'
    ];

    /**
     *  A un Planning on associe un utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

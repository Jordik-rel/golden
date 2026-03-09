<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'liste_permission'
    ];

    /**
     * A une permission, on associe une ou plusieurs roles
     */
    public function role():BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}

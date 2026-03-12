<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'libelle_role'
    ];

    /**
     * A un role , on associe un ou plusieurs utilisateurs
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * A un role, on associe une ou plusieurs permissions
     */
    public function permission(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     *  Vérification des acces du role
     */
    public function hasPermission(string $permissionName): bool
    {
        return $this->permission->contains('liste_permission', $permissionName);
    }
}

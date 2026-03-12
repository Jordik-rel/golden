<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'password',
        'tel',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * A un utilisateur , on associe un role
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * A un utilisateur , on associe un ou plusieurs palnning
     */
    public function plannings(): HasMany
    {
        return $this->hasMany(Planning::class);
    }

    /**
     * A un ustilisateur, on associe un ou plusieurs mouvment de stock
     */
    public function mouvements():HasMany
    {
        return $this->hasMany(MouvementStock::class);
    }

    /**
     * A un utilisateur, on associe un ou plusieurs inventaire
     */
    public function inventaires():HasMany
    {
        return $this->hasMany(Inventaire::class);
    }

    /**
     * A un utilisateur, on associe un ou plusieurs details d'inventaire
     */
    public function details():HasMany
    {
        return $this->hasMany(DetailInventaire::class);
    }

    /**
     * A un utilisateur ,on associe une ou plusieurs production journaliere
     */
    public function production():HasMany
    {
        return $this->hasMany(ProductionJournaliere::class);
    }

    /**
     * Vérifie si l'utilisateur dispose de la permission
     */
    public function hasPermission(string $permissionName): bool 
    {
        return $this->role?->hasPermission($permissionName) ?? false;
    }
}

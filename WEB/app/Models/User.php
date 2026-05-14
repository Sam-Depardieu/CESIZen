<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'id_role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    public function emotionRecords(): HasMany
    {
        return $this->hasMany(EmotionRecord::class, 'user_id');
    }

    public function favoritedActivities(): BelongsToMany
    {
        return $this->belongsToMany(RelaxationActivity::class, 'favorites', 'id_user', 'id_activite');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Autorise l'accès si l'utilisateur est actif et a le rôle 'Admin'
        return $this->is_active && $this->role && $this->role->libelle === 'Admin';
    }
}

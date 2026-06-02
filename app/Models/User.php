<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // ← tambahkan ini
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail // ← implements ini
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
        ];
    }

    // ===== RELASI =====

    public function chefProfile()
    {
        return $this->hasOne(ChefProfile::class);
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    // ===== HELPERS =====

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isChef(): bool
    {
        return $this->role === 'chef';
    }
    public function isMember(): bool
    {
        return $this->role === 'member';
    }
}
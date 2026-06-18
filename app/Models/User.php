<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'otp_code',
        'otp_expires_at',
        'is_blocked',
        'google_id',
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
            'otp_expires_at' => 'datetime',
            'is_blocked' => 'boolean',
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

    public function comments()
    {
        return $this->hasMany(Comment::class);
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

    // ===== ACCESSOR =====

    public function getFotoAttribute(): ?string
    {
        return $this->chefProfile?->foto;
    }

    public function getBioAttribute(): ?string
    {
        return $this->chefProfile?->bio;
    }

    public function sendEmailVerificationNotification()
    {
        $otp = sprintf('%06d', mt_rand(0, 999999));

        $this->forceFill([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(15),
        ])->save();

        $this->notify(new \App\Notifications\SendOtpVerification($otp));
    }
}
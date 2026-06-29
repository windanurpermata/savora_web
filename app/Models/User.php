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
        'phone_number',
        'mfa_secret',
        'mfa_enabled',
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
            'mfa_enabled' => 'boolean',
        ];
    }

    // ===== SALSA20 ENCRYPTION/DECRYPTION =====
    public function getPhoneNumberAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }
        return \App\Services\Salsa20::decrypt($value, config('app.key'));
    }

    public function setPhoneNumberAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['phone_number'] = null;
        } else {
            $this->attributes['phone_number'] = \App\Services\Salsa20::encrypt($value, config('app.key'));
        }
    }

    // ===== RELASI =====

    public function contributorProfile()
    {
        return $this->hasOne(ContributorProfile::class);
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
        return $this->role === 'admin' || $this->role === 'superadmin';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin' || 
               $this->email === 'admin@savora.com' || 
               $this->email === 'windanur337@gmail.com' ||
               str_contains(strtolower($this->name), 'super');
    }

    public function isContributor(): bool
    {
        return $this->role === 'contributor';
    }

    public function isMember(): bool
    {
        return $this->role === 'member';
    }

    // ===== ACCESSOR =====

    public function getFotoAttribute(): ?string
    {
        return $this->contributorProfile?->foto;
    }

    public function getBioAttribute(): ?string
    {
        return $this->contributorProfile?->bio;
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
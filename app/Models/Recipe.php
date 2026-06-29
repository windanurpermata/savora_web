<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'judul',
        'deskripsi',
        'gambar',
        'waktu_memasak',
        'porsi',
    ];

    // ===== SALSA20 ENCRYPTION/DECRYPTION =====
    public function getDeskripsiAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }
        return \App\Services\Salsa20::decrypt($value, config('app.key'));
    }

    public function setDeskripsiAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['deskripsi'] = null;
        } else {
            $this->attributes['deskripsi'] = \App\Services\Salsa20::encrypt($value, config('app.key'));
        }
    }

    // ===== RELASI =====

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }

    public function steps()
    {
        return $this->hasMany(Step::class)->orderBy('urutan');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }
}
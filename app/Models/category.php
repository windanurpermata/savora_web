<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['nama', 'emoji'];

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}
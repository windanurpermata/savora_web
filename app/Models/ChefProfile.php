<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChefProfile extends Model
{
    protected $fillable = ['user_id', 'bio', 'foto', 'spesialisasi'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
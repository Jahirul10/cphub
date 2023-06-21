<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class student extends Model
{
    // use HasFactory;

    public function submission(): HasMany
    {
        return $this->hasMany(submissions::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class problem extends Model
{
    // use HasFactory;

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
}

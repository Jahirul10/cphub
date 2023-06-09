<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Problem extends Model
{
    // use HasFactory;
    protected $keyType = 'string';

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class submissions extends Model
{
    // use HasFactory;

    public function student(): BelongsTo
    {
        return $this->belongsTo(student::class);
    }

    public function problem(): BelongsTo
    {
        Log::info('Retrieving problem for submission ' . $this->id);
        return $this->belongsTo(Problem::class);
    }
}

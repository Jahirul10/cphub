<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class handle extends Model
{
    // use HasFactory;

    public function student()
    {
        return $this->belongsTo(students::class);
    }
}
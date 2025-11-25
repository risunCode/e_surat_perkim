<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classification extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'description',
    ];

    public function letters(): HasMany
    {
        return $this->hasMany(Letter::class, 'classification_code', 'code');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LetterStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
    ];

    public function dispositions(): HasMany
    {
        return $this->hasMany(Disposition::class, 'letter_status');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'value',
    ];

    public static function get(string $code, $default = null)
    {
        $config = static::where('code', $code)->first();
        return $config ? $config->value : $default;
    }

    public static function set(string $code, string $value): void
    {
        static::updateOrCreate(
            ['code' => $code],
            ['value' => $value]
        );
    }
}

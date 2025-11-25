<?php

namespace App\Enums;

enum LetterType: string
{
    case INCOMING = 'incoming';
    case OUTGOING = 'outgoing';

    public function label(): string
    {
        return match($this) {
            self::INCOMING => 'Surat Masuk',
            self::OUTGOING => 'Surat Keluar',
        };
    }

    public static function options(): array
    {
        return [
            self::INCOMING->value => self::INCOMING->label(),
            self::OUTGOING->value => self::OUTGOING->label(),
        ];
    }
}

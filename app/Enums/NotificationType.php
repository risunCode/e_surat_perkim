<?php

namespace App\Enums;

enum NotificationType: string
{
    case INCOMING = 'incoming';
    case OUTGOING = 'outgoing';
    case DISPOSITION = 'disposition';
    case TRACKING = 'tracking';
    case OUTGOING_STATUS_UPDATED = 'outgoing_status_updated';

    public function label(): string
    {
        return match($this) {
            self::INCOMING => 'Surat Masuk',
            self::OUTGOING => 'Surat Keluar',
            self::DISPOSITION => 'Disposisi',
            self::TRACKING => 'Tracking',
            self::OUTGOING_STATUS_UPDATED => 'Status Update',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::INCOMING => 'bx-envelope-open',
            self::OUTGOING => 'bx-send',
            self::DISPOSITION => 'bx-transfer',
            self::TRACKING => 'bx-map',
            self::OUTGOING_STATUS_UPDATED => 'bx-check-circle',
        };
    }
}

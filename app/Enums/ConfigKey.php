<?php

namespace App\Enums;

enum ConfigKey: string
{
    case APP_NAME = 'app_name';
    case APP_DESCRIPTION = 'app_description';
    case ORGANIZATION_NAME = 'organization_name';
    case ORGANIZATION_ADDRESS = 'organization_address';
    case NOTIFICATION_REFRESH_INTERVAL = 'notification_refresh_interval';

    public function label(): string
    {
        return match($this) {
            self::APP_NAME => 'Nama Aplikasi',
            self::APP_DESCRIPTION => 'Deskripsi Aplikasi',
            self::ORGANIZATION_NAME => 'Nama Organisasi',
            self::ORGANIZATION_ADDRESS => 'Alamat Organisasi',
            self::NOTIFICATION_REFRESH_INTERVAL => 'Interval Refresh Notifikasi (detik)',
        };
    }

    public function defaultValue(): string
    {
        return match($this) {
            self::APP_NAME => 'E-Surat Perkim',
            self::APP_DESCRIPTION => 'Sistem Manajemen Surat Elektronik',
            self::ORGANIZATION_NAME => 'Dinas Perumahan dan Permukiman',
            self::ORGANIZATION_ADDRESS => '-',
            self::NOTIFICATION_REFRESH_INTERVAL => '120',
        };
    }
}

<?php

namespace Database\Seeders;

use App\Enums\ConfigKey;
use App\Models\Config;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ConfigKey::cases() as $key) {
            Config::create([
                'code' => $key->value,
                'value' => $key->defaultValue(),
            ]);
        }
    }
}

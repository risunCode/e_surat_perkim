<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ClassificationSeeder::class,
            LetterStatusSeeder::class,
            ConfigSeeder::class,
            UploadConfigSeeder::class,
            // Admin & Reference Codes: gunakan migraplus SQL files
        ]);
    }
}

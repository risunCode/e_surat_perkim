<?php

namespace Database\Seeders;

use App\Models\LetterStatus;
use Illuminate\Database\Seeder;

class LetterStatusSeeder extends Seeder
{
    public function run(): void
    {
        $statuses = [
            ['status' => 'Pending'],
            ['status' => 'Diproses'],
            ['status' => 'Selesai'],
            ['status' => 'Ditolak'],
            ['status' => 'Urgent'],
        ];

        foreach ($statuses as $status) {
            LetterStatus::create($status);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Classification;
use Illuminate\Database\Seeder;

class ClassificationSeeder extends Seeder
{
    public function run(): void
    {
        $classifications = [
            ['code' => '001', 'type' => 'Umum', 'description' => 'Surat-surat umum'],
            ['code' => '002', 'type' => 'Kepegawaian', 'description' => 'Surat-surat kepegawaian'],
            ['code' => '003', 'type' => 'Keuangan', 'description' => 'Surat-surat keuangan'],
            ['code' => '004', 'type' => 'Perlengkapan', 'description' => 'Surat-surat perlengkapan'],
            ['code' => '005', 'type' => 'Perizinan', 'description' => 'Surat-surat perizinan'],
            ['code' => '006', 'type' => 'Pengaduan', 'description' => 'Surat-surat pengaduan'],
            ['code' => '007', 'type' => 'Undangan', 'description' => 'Surat-surat undangan'],
            ['code' => '008', 'type' => 'Pemberitahuan', 'description' => 'Surat-surat pemberitahuan'],
        ];

        foreach ($classifications as $classification) {
            Classification::create($classification);
        }
    }
}

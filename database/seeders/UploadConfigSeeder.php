<?php

namespace Database\Seeders;

use App\Models\Config;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UploadConfigSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $configs = [
            ['code' => 'upload_max_file_size', 'value' => '15'],
            ['code' => 'upload_max_total_size', 'value' => '15'],
            ['code' => 'upload_allowed_types', 'value' => 'pdf,doc,docx,ppt,pptx,txt,jpg,jpeg,png,gif'],
            ['code' => 'upload_max_files', 'value' => '20'],
        ];

        foreach ($configs as $config) {
            Config::firstOrCreate(
                ['code' => $config['code']],
                ['value' => $config['value']]
            );
        }
    }
}

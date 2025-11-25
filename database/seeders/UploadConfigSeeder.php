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
            [
                'key' => 'upload_max_file_size',
                'value' => '15',
                'description' => 'Maximum file size per file in MB'
            ],
            [
                'key' => 'upload_max_total_size',
                'value' => '15',
                'description' => 'Maximum total size for all files in MB'
            ],
            [
                'key' => 'upload_allowed_types',
                'value' => 'pdf,doc,docx,ppt,pptx,txt,jpg,jpeg,png,gif',
                'description' => 'Allowed file extensions (comma separated)'
            ],
            [
                'key' => 'upload_max_files',
                'value' => '20',
                'description' => 'Maximum number of files that can be uploaded at once'
            ],
        ];

        foreach ($configs as $config) {
            Config::firstOrCreate(
                ['key' => $config['key']],
                [
                    'value' => $config['value'],
                    'description' => $config['description']
                ]
            );
        }
    }
}

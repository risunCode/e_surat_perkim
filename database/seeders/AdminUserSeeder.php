<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\ReferenceCode;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin user (skip if exists)
        User::firstOrCreate(
            ['email' => 'admin@perkim.go.id'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('perkim2025x'),
                'role' => Role::ADMIN->value,
                'is_active' => true,
                'security_setup_completed' => true,
                'security_question' => 'Siapa nama hewan peliharaan pertama Anda?',
                'security_answer' => Hash::make('admin'),
            ]
        );

        // Create reference codes (skip if exists)
        ReferenceCode::firstOrCreate(
            ['code' => 'ADMIN2025'],
            [
                'name' => 'Kode Admin 2025',
                'role' => Role::ADMIN->value,
                'max_usage' => 5,
                'is_active' => true,
                'created_by' => 1,
            ]
        );

        ReferenceCode::firstOrCreate(
            ['code' => 'STAFF2025'],
            [
                'name' => 'Kode Staff 2025',
                'role' => Role::STAFF->value,
                'max_usage' => 9999, // practically unlimited
                'is_active' => true,
                'created_by' => 1,
            ]
        );
    }
}

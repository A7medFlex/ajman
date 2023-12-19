<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'سياسات عجمان',
            'job_name' => 'مدير النظام',
            'email' => 'policy@ajman.ae',
            'password' => 'P0licy@2023',
            'is_admin' => true,
        ]);
    }
}

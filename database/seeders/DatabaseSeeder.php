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
            'name' => 'Ahmed Ragab',
            'job_name' => 'مدير النظام',
            'email' => 'om5280201@gmail.com',
            'is_admin' => true,
        ]);
    }
}

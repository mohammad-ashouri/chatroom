<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('123456789'),
        ]);
        User::create([
            'name' => 'Test User2',
            'email' => 'test2@example.com',
            'password' => bcrypt('123456789'),
        ]);
        User::create([
            'name' => 'Test User3',
            'email' => 'test3@example.com',
            'password' => bcrypt('123456789'),
        ]);
        User::create([
            'name' => 'Test User4',
            'email' => 'test4@example.com',
            'password' => bcrypt('123456789'),
        ]);
    }
}

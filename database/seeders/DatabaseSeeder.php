<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'is_admin' => true,
            'name' => 'Kail',
            'email' => 'myhkail.mendoza@gmail.com',
            'password' => bcrypt('Soul2001'),
            'date_of_birth' => now(),
        ]);

        User::create([
            'name' => 'Suzuka',
            'email' => 'rinrin032793@gmail.com',
            'password' => bcrypt('Soul2001'),
            'date_of_birth' => now(),
        ]);
    }
}

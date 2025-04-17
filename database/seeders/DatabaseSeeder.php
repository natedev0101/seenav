<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Database\Seeders\RankSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RankSeeder::class,
        ]);

        // Create admin user first
        User::factory()->create([
            'charactername' => 'Dr. Pietro Burns',
            'username' => 'test',
            'password' => bcrypt('test'),
            'isAdmin' => 1,
            'canGiveAdmin' => 1,
        ]);
        User::factory()->create([
            'charactername' => 'Maximillian Miller',
            'username' => 'Nate',
            'password' => bcrypt('Nate2025'),
            'isAdmin' => 1,
            'canGiveAdmin' => 1,
            'is_superadmin' => 1,
        ]);
       
        User::factory()->create([
            'charactername' => 'Ethan Chambley',
            'username' => 'Ethan',
            'password' => bcrypt('Ethan2025'),
            'isAdmin' => 1,
            'canGiveAdmin' => 1,
        ]);
    }
}

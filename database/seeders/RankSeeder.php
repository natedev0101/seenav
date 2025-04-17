<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset the auto-increment to 1
        Schema::disableForeignKeyConstraints();
        DB::table('ranks')->truncate();
        Schema::enableForeignKeyConstraints();

        $ranks = [
            ['name' => 'Tanuló', 'color' => '#ffffff'],
            ['name' => 'Hallgató', 'color' => '#fdf782'],
            ['name' => 'Őrmester', 'color' => '#fdf782'],
            ['name' => 'Törozsőrmester', 'color' => '#fdf782'],
            ['name' => 'Főtörzsőrmester', 'color' => '#fdf782'],
            ['name' => 'Zászlós', 'color' => '#fdf782'],
            ['name' => 'Törzszászlós', 'color' => '#fdf782'],
            ['name' => 'Főtörzszászlóstól', 'color' => '#fdf782'],
            ['name' => 'Hadnagy', 'color' => '#2b8d88'],
            ['name' => 'Főhadnagy', 'color' => '#2b8d88'],
            ['name' => 'Százados', 'color' => '#ad1457'],
            ['name' => 'Őrnagy', 'color' => '#ad1457'],
            ['name' => 'Alezredes', 'color' => '#ad1457'],
            ['name' => 'Ezredes', 'color' => '#ad1457'],
            ['name' => 'Dandártábornok', 'color' => '#ad1457'],
            ['name' => 'Vezérőrnagy', 'color' => '#ad1457'],
            ['name' => 'Altábornagy', 'color' => '#ad1457'],
            ['name' => 'Vezérezredes', 'color' => '#ad1457'],
        ];

        foreach ($ranks as $rank) {
            DB::table('ranks')->insert([
                'name' => $rank['name'],
                'color' => $rank['color'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubdivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subdivisions = [
            [
                'name' => 'MERKUR Parancsnok',
                'color' => '#464646',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MERKUR Kiképző',
                'color' => '#464646',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MERKUR',
                'color' => '#464646',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'KNyF Főosztályvezető',
                'color' => '#c27c0e',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'KNyF',
                'color' => '#c27c0e',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Adóigazgatóság Osztályvezető',
                'color' => '#0aa500',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Adóigazgatóság',
                'color' => '#0aa500',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Képzési Intézet Igazgató',
                'color' => '#3eb3ff',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Képzési Intézet Oktató',
                'color' => '#3eb3ff',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Szanitéc',
                'color' => '#ff0000',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Belső Ellenőrzési Osztály',
                'color' => '#f1c40f',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('subdivisions')->insert($subdivisions);
    }
}

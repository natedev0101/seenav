<?php

namespace Database\Seeders;

use App\Models\VersionUpdate;
use Illuminate\Database\Seeder;

class VersionUpdateSeeder extends Seeder
{
    public function run(): void
    {
        $versions = [
            [
                'version' => '1.0.0',
                'color' => 'blue',
                'is_active' => true
            ],
            [
                'version' => '1.1.0',
                'color' => 'green',
                'is_active' => false
            ],
            [
                'version' => '1.2.0-beta',
                'color' => 'purple',
                'is_active' => false
            ],
            [
                'version' => '1.3.0-rc',
                'color' => 'orange',
                'is_active' => false
            ],
            [
                'version' => '2.0.0',
                'color' => 'red',
                'is_active' => false
            ],
            [
                'version' => '2.1.0-dev',
                'color' => 'yellow',
                'is_active' => false
            ],
        ];

        foreach ($versions as $version) {
            VersionUpdate::create($version);
        }
    }
}

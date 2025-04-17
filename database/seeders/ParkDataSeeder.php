<?php

namespace Database\Seeders;

use App\Models\ParkData;
use Illuminate\Database\Seeder;

class ParkDataSeeder extends Seeder
{
    public function run(): void
    {
        // Előre definiált parkolóhelyek
        $parkingSpots = [
            // Bal oldali parkolók (1-es csoport - széles)
            [
                'owner' => null,
                'type' => 'free',
                'panel' => 'left',
                'parkGroup' => 1,
                'x' => 320,
                'y' => 250
            ],
            [
                'owner' => null,
                'type' => 'free',
                'panel' => 'left',
                'parkGroup' => 1,
                'x' => 320,
                'y' => 310
            ],
            [
                'owner' => null,
                'type' => 'free',
                'panel' => 'left',
                'parkGroup' => 1,
                'x' => 320,
                'y' => 370
            ],
            [
                'owner' => null,
                'type' => 'free',
                'panel' => 'left',
                'parkGroup' => 1,
                'x' => 320,
                'y' => 430
            ],
            // Jobb oldali parkolók (1-es csoport - széles)
            [
                'owner' => null,
                'type' => 'free',
                'panel' => 'right',
                'parkGroup' => 1,
                'x' => 1480,
                'y' => 250
            ],
            [
                'owner' => null,
                'type' => 'free',
                'panel' => 'right',
                'parkGroup' => 1,
                'x' => 1480,
                'y' => 310
            ],
            [
                'owner' => null,
                'type' => 'free',
                'panel' => 'right',
                'parkGroup' => 1,
                'x' => 1480,
                'y' => 370
            ],
            [
                'owner' => null,
                'type' => 'free',
                'panel' => 'right',
                'parkGroup' => 1,
                'x' => 1480,
                'y' => 430
            ],
            // Felső parkolók (2-es csoport - magas)
            [
                'owner' => null,
                'type' => 'free',
                'panel' => 'top',
                'parkGroup' => 2,
                'x' => 600,
                'y' => 150
            ],
            [
                'owner' => null,
                'type' => 'free',
                'panel' => 'top',
                'parkGroup' => 2,
                'x' => 660,
                'y' => 150
            ],
            [
                'owner' => null,
                'type' => 'free',
                'panel' => 'top',
                'parkGroup' => 2,
                'x' => 720,
                'y' => 150
            ],
            [
                'owner' => null,
                'type' => 'free',
                'panel' => 'top',
                'parkGroup' => 2,
                'x' => 780,
                'y' => 150
            ],
            // Alsó parkolók (2-es csoport - magas)
            [
                'owner' => null,
                'type' => 'free',
                'panel' => 'bottom',
                'parkGroup' => 2,
                'x' => 600,
                'y' => 830
            ],
            [
                'owner' => null,
                'type' => 'free',
                'panel' => 'bottom',
                'parkGroup' => 2,
                'x' => 660,
                'y' => 830
            ],
            [
                'owner' => null,
                'type' => 'free',
                'panel' => 'bottom',
                'parkGroup' => 2,
                'x' => 720,
                'y' => 830
            ],
            [
                'owner' => null,
                'type' => 'free',
                'panel' => 'bottom',
                'parkGroup' => 2,
                'x' => 780,
                'y' => 830
            ]
        ];

        foreach ($parkingSpots as $index => $spot) {
            $spot['id'] = $index + 1;
            ParkData::create($spot);
        }
    }
}

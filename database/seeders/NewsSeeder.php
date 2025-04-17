<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\News;
use App\Models\User;
use Carbon\Carbon;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Superadmin felhasználó keresése
        $admin = User::where('is_superadmin', true)->first();

        if (!$admin) {
            $this->command->error('Superadmin felhasználó nem található!');
            return;
        }

        // Hírek létrehozása
        $news = [
            [
                'title' => 'Új funkciók az adatbázisban',
                'content' => 'Az adatbázis rendszer frissítésre került. Mostantól több új funkció is elérhető, többek között a hírek kezelése és a felhasználói értesítések.',
                'type' => 'info',
                'is_active' => true,
                'created_by' => $admin->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'title' => 'Karbantartás előtt',
                'content' => 'A rendszer holnap 02:00 és 04:00 között karbantartás miatt nem lesz elérhető. Kérjük, tervezze ennek megfelelően a munkáját!',
                'type' => 'warning',
                'is_active' => true,
                'created_by' => $admin->id,
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(2)
            ],
            [
                'title' => 'Sikeres frissítés',
                'content' => 'A rendszer frissítése sikeresen megtörtént. Az új funkciók már elérhetőek minden felhasználó számára.',
                'type' => 'success',
                'is_active' => true,
                'created_by' => $admin->id,
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1)
            ],
            [
                'title' => 'Biztonsági figyelmeztetés',
                'content' => 'Kérjük minden felhasználót, hogy változtassa meg a jelszavát a következő bejelentkezéskor a biztonság növelése érdekében.',
                'type' => 'danger',
                'is_active' => true,
                'created_by' => $admin->id,
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2)
            ]
        ];

        foreach ($news as $item) {
            News::create($item);
        }

        $this->command->info('Hírek sikeresen létrehozva!');
    }
}

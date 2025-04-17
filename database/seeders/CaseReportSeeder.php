<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CaseReport;
use App\Models\User;
use Carbon\Carbon;
use Faker\Factory as Faker;

class CaseReportSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('hu_HU');
        
        // Get some users to work with
        $users = User::all();
        $admins = User::where('isAdmin', true)->get();
        
        // Case types
        $caseTypes = ['Igazoltatás', 'Előállítás'];
        
        // Generate 50 random case reports
        for ($i = 0; $i < 1000; $i++) {
            $status = $faker->randomElement(['PENDING', 'APPROVED', 'REJECTED']);
            $user = $users->random();
            
            $caseReport = new CaseReport();
            $caseReport->user_id = $user->id;
            $caseReport->first_partner = $faker->name;
            $caseReport->second_partner = $faker->optional(0.7)->name; // 70% chance to have second partner
            $caseReport->case_type = $faker->randomElement($caseTypes);
            $caseReport->citizen_name = $faker->name;
            $caseReport->action_time = $faker->dateTimeBetween('-1 month', 'now');
            $caseReport->reason = $faker->sentence(10);
            $caseReport->fine_amount = $faker->numberBetween(5000, 500000);
            $caseReport->status = $status;
            
            // If status is not pending, add handler
            if ($status !== 'PENDING') {
                $caseReport->handled_by = $admins->random()->id;
                if ($status === 'REJECTED') {
                    $caseReport->rejection_reason = $faker->sentence(5);
                }
            }
            
            // 30% chance to have image
            if ($faker->boolean(30)) {
                $caseReport->image_link = 'https://picsum.photos/800/600?random=' . uniqid();
            }
            
            $caseReport->created_at = $faker->dateTimeBetween('-1 month', 'now');
            $caseReport->updated_at = $faker->dateTimeBetween($caseReport->created_at, 'now');
            
            $caseReport->save();
        }
    }
}

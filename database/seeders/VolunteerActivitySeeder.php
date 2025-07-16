<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\VolunteerActivity;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VolunteerActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $organizations = Organization::pluck('id')->toArray();

        foreach($organizations as $organization){
            for($i = 0; $i < rand(0,3); $i++){
                VolunteerActivity::create([
                    'organization_id' => $organization,
                    'title' => $faker->sentence(3),
                    'description' => $faker->paragraph,
                    'detail_activity' => $faker->paragraph(5),
                    'location' => $faker->city,
                    'status' => $faker->randomElement(['open', 'closed', 'completed', 'cancelled']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

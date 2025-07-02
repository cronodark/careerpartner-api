<?php

namespace Database\Seeders;

use App\Models\Experience;
use App\Models\Talent;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $talents = Talent::all()->pluck('id')->toArray();

        foreach($talents as $talent){
            for($i = 0; $i < rand(0, 3); $i++){
                Experience::create([
                    'talent_id' => $talent,
                    'description' => $faker->paragraph(rand(1, 3))
                ]);
            }
        }
    }
}

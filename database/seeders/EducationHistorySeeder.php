<?php

namespace Database\Seeders;

use App\Models\EducationHistory;
use App\Models\Talent;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EducationHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $talents = Talent::all()->pluck('id')->toArray();

        foreach ($talents as $talentId) {
            for ($i = 0; $i < rand(1, 2); $i++) {
                EducationHistory::create([
                    'talent_id' => $talentId,
                    'institution_name' => $faker->company,
                    'field_of_study' => $faker->word,
                    'start_year' => $faker->year,
                    'end_year' => $faker->year,
                ]);
            }
        }
    }
}

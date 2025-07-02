<?php

namespace Database\Seeders;

use App\Models\Achievement;
use App\Models\Talent;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $talents = Talent::all()->pluck('id')->toArray();
        foreach ($talents as $talentId) {
            for ($i = 0; $i < rand(0, 3); $i++) {
                Achievement::create([
                    'talent_id' => $talentId,
                    'title' => $faker->sentence(rand(3, 6)),
                    'nomination' => $faker->word,
                    'year' => $faker->year(),
                ]);
            }
        }
    }
}

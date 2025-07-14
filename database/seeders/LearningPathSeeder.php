<?php

namespace Database\Seeders;

use App\Models\LearningPath;
use App\Models\Talent;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LearningPathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $talents = Talent::all()->pluck('id')->toArray();

        foreach ($talents as $talentId) {
            for ($i = 0; $i < rand(0, 5); $i++) {
                LearningPath::create([
                    'title' => $faker->sentence(3),
                    'url' => $faker->url,
                    'is_done' => $faker->boolean,
                    'talent_id' => $talentId,
                ]);
            }
        }
    }
}

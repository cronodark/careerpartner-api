<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Talent;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
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
                Project::create([
                    'talent_id' => $talent,
                    'title' => $faker->sentence(rand(3, 6)),
                    'image' => $faker->imageUrl(640, 480, 'projects', true, 'Project'),
                    'link' => $faker->url,
                    'year' => $faker->year(),
                ]);
            }
        }
    }
}

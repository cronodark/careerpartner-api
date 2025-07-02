<?php

namespace Database\Seeders;

use App\Models\Interest;
use App\Models\Talent;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InterestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $talents = Talent::all()->pluck('id')->toArray();
        $interest = [
            'Business Analyst',
            'Data Analyst',
            'Data Scientist',
            'Developer',
            'Martketing',
            'Finance',
            'Design',
            'Science',
            'Engineering',
            'Game Development',
        ];

        foreach ($talents as $talentId) {
            for($i = 0; $i < rand(1, 3); $i++) {
                Interest::create([
                    'talent_id' => $talentId,
                    'name' => $faker->randomElement($interest)
                ]);
            }
        }
    }
}

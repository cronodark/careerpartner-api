<?php

namespace Database\Seeders;

use App\Models\Talent;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TalentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $userTalents = User::where('role', 'talent')->pluck('id')->toArray();

        foreach($userTalents as $user){
            Talent::create([
                'user_id' => $user,
                'current_education' => $faker->randomElement(['High School', 'Bachelor\'s Degree', 'Master\'s Degree', 'PhD']),
                'goal_career' => $faker->jobTitle,
                'job_opportunity' => $faker->randomElement(['Junior Developer', 'Medium Developer', 'Senior Developer']),
                'description' => $faker->paragraph(rand(1, 3)),
                'expected_salary' => $faker->numberBetween(30000, 120000),
                'date_of_birth' => $faker->date(),
            ]);
        }
    }
}

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
                'major' => $faker->randomElement(['Computer Science', 'Engineering', 'Business', 'Arts', 'Science']),
                'interests' => $faker->randomElement(['Tech', 'Sosial', 'Environment', 'Health', 'Space']),
            ]);
        }
    }
}

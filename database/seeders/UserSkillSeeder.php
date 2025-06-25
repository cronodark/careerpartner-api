<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\User;
use App\Models\UserSkill;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $usersTalent = User::where('role', 'talent')->pluck('id')->toArray();
        $skills = Skill::pluck('id')->toArray();

        foreach ($usersTalent as $user) {
            for($i = 0; $i < rand(1, 5); $i++) {
                $skillId = $faker->randomElement($skills);
                UserSkill::create([
                    'talent_id' => $user,
                    'skill_id' => $skillId,
                    'proficiency' => $faker->randomElement(['beginner', 'intermediate', 'advanced']),
                    'years_of_experience' => $faker->numberBetween(0, 20),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

    }
}

<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\VolunteerActivity;
use App\Models\VolunteerActivitySkill;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VolunteerActivitySkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $volunteerActivities = VolunteerActivity::pluck('id')->toArray();
        $skills = Skill::pluck('id')->toArray();

        foreach ($volunteerActivities as $activityId) {
            for ($i = 0; $i < rand(1, 5); $i++) {
                VolunteerActivitySkill::create([
                    'volunteer_activity_id' => $activityId,
                    'skill_id' => $faker->randomElement($skills),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

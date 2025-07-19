<?php

namespace Database\Seeders;

use App\Models\Skill;
use App\Models\VolunteerActivity;
use App\Models\VolunteerActivitySkill;
use Carbon\Carbon;
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
        $volunteerActivities = VolunteerActivity::all();
        $allSkillIds = Skill::pluck('id')->toArray();

        foreach ($volunteerActivities as $activity) {
            $numSkillsToAssign = rand(1, min(2, count($allSkillIds)));

            $chosenSkillIds = $faker->randomElements($allSkillIds, $numSkillsToAssign);

            foreach ($chosenSkillIds as $skillId) {
                $exists = VolunteerActivitySkill::where('volunteer_activity_id', $activity->id)
                    ->where('skill_id', $skillId)
                    ->exists();

                if (!$exists) {
                    VolunteerActivitySkill::create([
                        'volunteer_activity_id' => $activity->id,
                        'skill_id' => $skillId,
                        'created_at' => Carbon::now()->subDays(rand(1, 365)),
                        'updated_at' => Carbon::now()->subDays(rand(0, 30)),
                    ]);
                }
            }
        }
    }
}

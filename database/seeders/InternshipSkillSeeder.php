<?php

namespace Database\Seeders;

use App\Models\Internship;
use App\Models\InternshipSkill;
use App\Models\Skill;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InternshipSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $internships = Internship::all();
        $allSkillIds = Skill::pluck('id')->toArray();

        foreach ($internships as $internship) {
            // Determine how many unique skills this internship should have (e.g., 1 to 2)
            // Ensure you don't ask for more unique skills than available in $allSkillIds
            $numSkillsToAssign = rand(1, min(2, count($allSkillIds)));

            // Use randomElements (plural) to get a batch of unique skill IDs for this internship.
            // This guarantees uniqueness within the chosen batch for this specific internship.
            $chosenSkillIds = $faker->randomElements($allSkillIds, $numSkillsToAssign);

            foreach ($chosenSkillIds as $skillId) {
                // Check if the combination already exists to prevent duplicates on multiple runs
                // (though a database unique index is the primary guard)
                $exists = InternshipSkill::where('id', $internship->id)
                    ->where('skill_id', $skillId)
                    ->exists();

                if (!$exists) {
                    InternshipSkill::create([
                        'internship_id' => $internship->id,
                        'skill_id' => $skillId,
                        'created_at' => Carbon::now()->subDays(rand(1, 365)), // Add realistic timestamps
                        'updated_at' => Carbon::now()->subDays(rand(0, 30)),
                    ]);
                }
            }
        }
    }
}

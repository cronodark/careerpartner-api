<?php

namespace Database\Seeders;

use App\Models\Internship;
use App\Models\InternshipSkill;
use App\Models\Skill;
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
        $internships = Internship::pluck('id')->toArray();
        $skills = Skill::pluck('id')->toArray();

        foreach ($internships as $internshipId) {
            for ($i = 0; $i < rand(1, 5); $i++) {
                InternshipSkill::create([
                    'internship_id' => $internshipId,
                    'skill_id' => $faker->randomElement($skills),
                ]);
            }
        }
    }
}

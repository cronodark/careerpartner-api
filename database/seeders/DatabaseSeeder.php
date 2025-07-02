<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SkillSeeder::class,
            TalentSeeder::class,
            CompanySeeder::class,
            OrganizationSeeder::class,
            UserSkillSeeder::class,
            InternshipSeeder::class,
            InternshipSkillSeeder::class,
            VolunteerActivitySeeder::class,
            VolunteerActivitySkillSeeder::class,
            InterestsSeeder::class,
            AchievementSeeder::class,
            ExperienceSeeder::class,
            ProjectSeeder::class,
            EducationHistorySeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Internship;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InternshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $companies = Company::pluck('id')->toArray();

        foreach ($companies as $companyId) {
            for ($i = 0; $i < rand(1, 5); $i++) {
                Internship::create([
                    'company_id' => $companyId,
                    'title' => $faker->jobTitle,
                    'description' => $faker->paragraph,
                    'image_cover' => $faker->imageUrl(),
                    'location' => $faker->city,
                    'responsibilities' => $faker->text(100),
                    'requirements' => $faker->text(100),
                    'status' => $faker->randomElement(['open', 'closed', 'draft']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

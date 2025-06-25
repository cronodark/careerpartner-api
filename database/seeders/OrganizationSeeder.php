<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $userOrganizations = User::where('role', 'organization')->pluck('id')->toArray();

        foreach($userOrganizations as $user){
            Organization::create([
                'name' => $faker->company,
                'logo' => $faker->imageUrl(640, 480, 'business', true, 'Faker'),
                'description' => $faker->paragraph,
                'contact_email' => $faker->email,
                'contact_phone' => $faker->phoneNumber,
                'status' => $faker->randomElement(['active', 'inactive', 'pending']),
                'user_id' => $user,
            ]);
        }
    }
}

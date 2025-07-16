<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        $userCoompanies = User::where('role', 'company')->pluck('id')->toArray();

        foreach($userCoompanies as $user){
            Company::create([
                'name' => $faker->company,
                'logo' => $faker->imageUrl(640, 480, 'business', true, 'Faker'),
                'industry' => $faker->randomElement(['Technology', 'Finance', 'Healthcare', 'Education', 'Retail']),
                'description' => $faker->sentence(10),
                'headquarters_address' => $faker->address,
                'website' => $faker->url,
                'contact_email' => $faker->email,
                'contact_phone' => $faker->phoneNumber,
                'user_id' => $user,
            ]);
        }
    }
}

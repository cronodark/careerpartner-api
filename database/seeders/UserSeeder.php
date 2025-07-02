<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        User::create([
            'full_name' => $faker->name,
            'email' => 'talent1@mail.com',
            'password' => Hash::make('talent1'),
            'username' => 'talent1',
            'phone' => $faker->phoneNumber,
            'role' => 'talent',
            'profile_picture' => $faker->imageUrl(640, 480, 'people', true, 'Faker'),
        ]);

        User::create([
            'full_name' => $faker->name,
            'email' => 'talent2@mail.com',
            'password' => Hash::make('talent2'),
            'username' => 'talent2',
            'phone' => $faker->phoneNumber,
            'role' => 'talent',
            'profile_picture' => $faker->imageUrl(640, 480, 'people', true, 'Faker'),
        ]);

        User::create([
            'full_name' => $faker->name,
            'email' => 'company1@mail.com',
            'password' => Hash::make('company1'),
            'username' => 'company1',
            'phone' => $faker->phoneNumber,
            'role' => 'company',
            'profile_picture' => $faker->imageUrl(640, 480, 'people', true, 'Faker'),
        ]);

        User::create([
            'full_name' => $faker->name,
            'email' => 'company2@mail.com',
            'password' => Hash::make('company2'),
            'username' => 'company2',
            'phone' => $faker->phoneNumber,
            'role' => 'company',
            'profile_picture' => $faker->imageUrl(640, 480, 'people', true, 'Faker'),
        ]);

        User::create([
            'full_name' => $faker->name,
            'email' => 'organization1@mail.com',
            'password' => Hash::make('organization1'),
            'username' => 'organization1',
            'phone' => $faker->phoneNumber,
            'role' => 'organization',
            'profile_picture' => $faker->imageUrl(640, 480, 'people', true, 'Faker'),
        ]);

        User::create([
            'full_name' => $faker->name,
            'email' => 'organization2@mail.com',
            'password' => Hash::make('organization2'),
            'username' => 'organization2',
            'phone' => $faker->phoneNumber,
            'role' => 'organization',
            'profile_picture' => $faker->imageUrl(640, 480, 'people', true, 'Faker'),
        ]);
    }
}

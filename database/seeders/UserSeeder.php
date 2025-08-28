<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $users = [
            [
                'name' => 'Admin',
                'username' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('12341234'),
                'role' => 'admin',
                'email_verified' => 1,
            ],
            [
                'name' => 'Fan',
                'username' => 'Fan',
                'email' => 'fan@gmail.com',
                'password' => Hash::make('12341234'),
                'role' => 'fan',
                'email_verified' => 1,
            ],
            [
                'name' => 'Escort',
                'username' => 'Escort',
                'email' => 'escort@gmail.com',
                'password' => Hash::make('12341234'),
                'role' => 'escort',
                'email_verified' => 1,
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(['email' => $user['email']], $user);
        }
    }
}

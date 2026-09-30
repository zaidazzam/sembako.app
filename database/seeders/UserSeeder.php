<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@sembako.test',
            ],
            [
                'name' => 'Administrator',
                'password' => 'password',
                'role' => UserRole::ADMIN,
                'status' => true,
            ]
        );

        User::updateOrCreate(
            [
                'email' => 'budi@sembako.test',
            ],
            [
                'name' => 'Budi Petugas',
                'password' => 'password',
                'role' => UserRole::PETUGAS,
                'status' => true,
            ]
        );

        User::updateOrCreate(
            [
                'email' => 'andi@sembako.test',
            ],
            [
                'name' => 'Andi Petugas',
                'password' => 'password',
                'role' => UserRole::PETUGAS,
                'status' => true,
            ]
        );

        User::updateOrCreate(
            [
                'email' => 'warunga@sembako.test',
            ],
            [
                'name' => 'Warung A',
                'password' => 'password',
                'role' => UserRole::WARUNG,
                'status' => true,
            ]
        );

        User::updateOrCreate(
            [
                'email' => 'warungb@sembako.test',
            ],
            [
                'name' => 'Warung B',
                'password' => 'password',
                'role' => UserRole::WARUNG,
                'status' => true,
            ]
        );

        User::updateOrCreate(
            [
                'email' => 'warungc@sembako.test',
            ],
            [
                'name' => 'Warung C',
                'password' => 'password',
                'role' => UserRole::WARUNG,
                'status' => true,
            ]
        );
    }
}

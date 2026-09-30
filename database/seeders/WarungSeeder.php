<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Warung;
use Illuminate\Database\Seeder;

class WarungSeeder extends Seeder
{
    public function run(): void
    {
        $warungAUser = User::where(
            'email',
            'warunga@sembako.test'
        )->firstOrFail();

        $warungBUser = User::where(
            'email',
            'warungb@sembako.test'
        )->firstOrFail();

        $warungCUser = User::where(
            'email',
            'warungc@sembako.test'
        )->firstOrFail();

        Warung::updateOrCreate(
            [
                'code' => 'WAR-001',
            ],
            [
                'user_id' => $warungAUser->id,
                'name' => 'Warung A',
                'phone' => '081234567890',
                'address' => 'Jl. Contoh No. 1',
                'status' => true,
            ]
        );

        Warung::updateOrCreate(
            [
                'code' => 'WAR-002',
            ],
            [
                'user_id' => $warungBUser->id,
                'name' => 'Warung B',
                'phone' => '081234567891',
                'address' => 'Jl. Contoh No. 2',
                'status' => true,
            ]
        );

        Warung::updateOrCreate(
            [
                'code' => 'WAR-003',
            ],
            [
                'user_id' => $warungCUser->id,
                'name' => 'Warung C',
                'phone' => '081234567892',
                'address' => 'Jl. Contoh No. 3',
                'status' => true,
            ]
        );

        /*
         * Pastikan user yang digunakan memang role warung.
         */
        $warungUsers = [
            $warungAUser,
            $warungBUser,
            $warungCUser,
        ];

        foreach ($warungUsers as $user) {
            $user->update([
                'role' => UserRole::WARUNG,
            ]);
        }
    }
}

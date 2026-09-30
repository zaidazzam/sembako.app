<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Warung;
use Illuminate\Database\Seeder;

class PetugasWarungSeeder extends Seeder
{
    public function run(): void
    {
        $budi = User::where(
            'email',
            'budi@sembako.test'
        )->firstOrFail();

        $andi = User::where(
            'email',
            'andi@sembako.test'
        )->firstOrFail();

        $warungA = Warung::where(
            'code',
            'WAR-001'
        )->firstOrFail();

        $warungB = Warung::where(
            'code',
            'WAR-002'
        )->firstOrFail();

        $warungC = Warung::where(
            'code',
            'WAR-003'
        )->firstOrFail();

        $budi->assignedWarungs()->syncWithoutDetaching([
            $warungA->id,
            $warungB->id,
        ]);

        $andi->assignedWarungs()->syncWithoutDetaching([
            $warungC->id,
        ]);
    }
}

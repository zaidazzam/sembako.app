<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Mi Instan',
                'description' => 'Berbagai jenis mi instan',
            ],
            [
                'name' => 'Minyak',
                'description' => 'Minyak goreng dan sejenisnya',
            ],
            [
                'name' => 'Beras',
                'description' => 'Berbagai jenis beras',
            ],
            [
                'name' => 'Gula',
                'description' => 'Gula pasir dan gula lainnya',
            ],
            [
                'name' => 'Minuman',
                'description' => 'Minuman kemasan',
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                [
                    'name' => $category['name'],
                ],
                [
                    'description' => $category['description'],
                    'status' => true,
                ]
            );
        }
    }
}

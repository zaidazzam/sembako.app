<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category' => 'Mi Instan',
                'code' => 'MIE-001',
                'name' => 'Mi Instan Goreng',
                'unit' => 'dus',
                'price' => 125000,
                'minimum_stock' => 5,
            ],
            [
                'category' => 'Mi Instan',
                'code' => 'MIE-002',
                'name' => 'Mi Instan Soto',
                'unit' => 'dus',
                'price' => 120000,
                'minimum_stock' => 5,
            ],
            [
                'category' => 'Minyak',
                'code' => 'MIN-001',
                'name' => 'Minyak Goreng 1 Liter',
                'unit' => 'dus',
                'price' => 180000,
                'minimum_stock' => 5,
            ],
            [
                'category' => 'Beras',
                'code' => 'BRG-001',
                'name' => 'Beras Premium',
                'unit' => 'kg',
                'price' => 15000,
                'minimum_stock' => 20,
            ],
            [
                'category' => 'Gula',
                'code' => 'GLA-001',
                'name' => 'Gula Pasir',
                'unit' => 'kg',
                'price' => 17000,
                'minimum_stock' => 20,
            ],
            [
                'category' => 'Minuman',
                'code' => 'MNM-001',
                'name' => 'Air Mineral',
                'unit' => 'dus',
                'price' => 45000,
                'minimum_stock' => 5,
            ],
        ];

        foreach ($products as $item) {
            $category = Category::where(
                'name',
                $item['category']
            )->firstOrFail();

            Product::updateOrCreate(
                [
                    'code' => $item['code'],
                ],
                [
                    'category_id' => $category->id,
                    'name' => $item['name'],
                    'unit' => $item['unit'],
                    'price' => $item['price'],
                    'minimum_stock' => $item['minimum_stock'],
                    'status' => true,
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use App\Enums\StockMovementType;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;

class StockMovementSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where(
            'email',
            'admin@sembako.test'
        )->firstOrFail();

        $stockData = [
            'MIE-001' => 50,
            'MIE-002' => 30,
            'MIN-001' => 20,
            'BRG-001' => 100,
            'GLA-001' => 50,
            'MNM-001' => 25,
        ];

        foreach ($stockData as $code => $quantity) {
            $product = Product::where(
                'code',
                $code
            )->firstOrFail();

            StockMovement::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'type' => StockMovementType::IN->value,
                    'reference_type' => 'initial_stock',
                    'reference_id' => null,
                ],
                [
                    'quantity' => $quantity,
                    'notes' => 'Stok awal aplikasi',
                    'created_by' => $admin->id,
                ]
            );
        }
    }
}

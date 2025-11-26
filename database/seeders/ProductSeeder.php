<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Mobile Legends
            [
                'game'       => 'mobile_legends',
                'name'       => '86 Diamonds',
                'amount'     => 86,
                'price'      => 20000,
                'base_price' => 17000,
            ],
            [
                'game'       => 'mobile_legends',
                'name'       => '172 Diamonds',
                'amount'     => 172,
                'price'      => 39000,
                'base_price' => 34000,
            ],

            // Free Fire
            [
                'game'       => 'free_fire',
                'name'       => '100 Diamonds',
                'amount'     => 100,
                'price'      => 15000,
                'base_price' => 13000,
            ],
            [
                'game'       => 'free_fire',
                'name'       => '500 Diamonds',
                'amount'     => 500,
                'price'      => 70000,
                'base_price' => 60000,
            ],

            // Genshin
            [
                'game'       => 'genshin_impact',
                'name'       => 'Welkin Moon',
                'amount'     => 300,
                'price'      => 65000,
                'base_price' => 60000,
            ],
        ];

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}

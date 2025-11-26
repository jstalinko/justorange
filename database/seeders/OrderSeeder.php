<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        if ($products->count() == 0) {
            $this->command->warn("⚠️ Tidak ada product, jalankan ProductSeeder dulu!");
            return;
        }

        // Generate 500 dummy orders
        for ($i = 0; $i < 500; $i++) {

            $product = $products->random();

            Order::create([
                'product_id' => $product->id,
                'uid'        => rand(100000, 999999),
                'zone_id'    => rand(1000, 9999),
                'price'      => $product->price,
                'status'     => collect(['pending','processing','success','failed'])->random(),
                'transaction_id' => 'TRX-' . strtoupper(uniqid()),
                'created_at' => now()->subDays(rand(0, 90)), // random 3 bulan
            ]);
        }
    }
}

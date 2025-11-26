<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        if ($products->count() == 0) {
            $this->command->warn("⚠️ Jalankan ProductSeeder dulu!");
            return;
        }

        $targetIncome = 700_000_000; // 700 juta
        $targetProfit = 50_000_000;  // 50 juta
        $totalIncome  = 0;
        $totalProfit  = 0;

        $orderCount = 0;

        while ($totalIncome < $targetIncome || $totalProfit < $targetProfit) {

            $product = $products->random();

            $income = $product->price;
            $profit = $product->price - $product->base_price;

            Order::create([
                'product_id'     => $product->id,
                'uid'            => rand(100000, 999999),
                'zone_id'        => rand(1000, 9999),
                'price'          => $product->price,
                'status'         => 'success', // karena butuh income
                'transaction_id' => 'TRX-' . strtoupper(uniqid()),
                'created_at'     => Carbon::now()->subDays(rand(0, 90)),
            ]);

            $totalIncome += $income;
            $totalProfit += $profit;
            $orderCount++;

            if ($orderCount > 20000) break; // safety
        }

        $this->command->info("Order generated: {$orderCount}");
        $this->command->info("Total Income: " . number_format($totalIncome));
        $this->command->info("Total Profit: " . number_format($totalProfit));
    }
}

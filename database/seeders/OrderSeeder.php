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

        Order::truncate();

        // Target per bulan
        $TARGET_INCOME = 700_000_000; // 700 juta
        $TARGET_PROFIT = 50_000_000;  // 50 juta

        // Range Januari sampai 28 November tahun sekarang
        $year = now()->year;

        $months = [
            '01','02','03','04','05','06','07','08','09','10','11'
        ];

        foreach ($months as $month) {

            $startDate = Carbon::create($year, $month, 1);

            // November hanya sampai tanggal 28
            if ($month == '11') {
                $endDate = Carbon::create($year, 11, 28, 23, 59, 59);
            } else {
                $endDate = $startDate->copy()->endOfMonth();
            }

            $this->command->info("⏳ Generating orders for month: {$startDate->format('F')}");

            $totalIncome = 0;
            $totalProfit = 0;
            $orderCount  = 0;

            while ($totalIncome < $TARGET_INCOME || $totalProfit < $TARGET_PROFIT) {

                $product = $products->random();

                $income = $product->price;
                $profit = $product->price - $product->base_price;

                $randomDate = Carbon::createFromTimestamp(
                    rand($startDate->timestamp, $endDate->timestamp)
                );

                Order::create([
                    'product_id'     => $product->id,
                    'uid'            => rand(100000, 999999),
                    'zone_id'        => rand(1000, 9999),
                    'price'          => $product->price,
                    'status'         => 'success',
                    'transaction_id' => 'TRX-' . strtoupper(uniqid()),
                    'created_at'     => $randomDate,
                ]);

                $totalIncome += $income;
                $totalProfit += $profit;
                $orderCount++;

                if ($orderCount > 25000) break; // Safety limit
            }

            $this->command->info("✔ {$startDate->format('F')}: Orders = {$orderCount}");
            $this->command->info("Income: " . number_format($totalIncome));
            $this->command->info("Profit: " . number_format($totalProfit));
            $this->command->info("-----------------------------");
        }

        $this->command->info("🎉 DONE: ORDER SEEDER PER-BULAN UNTUK JAN - NOV");
    }
}

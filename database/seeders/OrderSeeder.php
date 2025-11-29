<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        if ($products->count() === 0) {
            $this->command->warn("⚠️ Jalankan ProductSeeder dulu!");
            return;
        }

        // TRUNCATE terlebih dahulu
        Order::truncate();

        $year = now()->year;

        for ($month = 1; $month <= 11; $month++) {

            // Random income target
            $TARGET_INCOME = rand(750_000_000, 1_700_000_000);

            // Persentase profit antara 4% sampai 7%
            $PERCENT = rand(4, 7) / 100; // 4% – 7%

            // Profit target = persentase × income target
            $TARGET_PROFIT = $TARGET_INCOME * $PERCENT;

            $startDate = Carbon::create($year, $month, 1, 0, 0, 0);

            if ($month === 11) {
                $endDate = Carbon::create($year, 11, 28, 23, 59, 59);
            } else {
                $endDate = Carbon::create($year, $month, 1)->endOfMonth();
            }

            $this->command->info("🔄 Generating {$startDate->format('F Y')} ...");
            $this->command->info("Target Income: Rp " . number_format($TARGET_INCOME));
            $this->command->info("Target Profit: Rp " . number_format($TARGET_PROFIT));
            $this->command->info("Profit Percent: " . ($PERCENT * 100) . "%");

            $totalIncome = 0;
            $totalProfit = 0;
            $orderCount  = 0;

            while ($totalIncome < $TARGET_INCOME && $totalProfit < $TARGET_PROFIT) {

                $product = $products->random();

                $income = $product->price;
                $profit = $product->price - $product->base_price;

                $randomTimestamp = rand($startDate->timestamp, $endDate->timestamp);

                Order::create([
                    'product_id'     => $product->id,
                    'uid'            => rand(100000, 999999),
                    'zone_id'        => rand(1000, 9999),
                    'price'          => $product->price,
                    'status'         => 'success',
                    'transaction_id' => 'TRX-' . strtoupper(uniqid()),
                    'created_at'     => Carbon::createFromTimestamp($randomTimestamp),
                ]);

                $totalIncome += $income;
                $totalProfit += $profit;
                $orderCount++;

                if ($orderCount > 50000) {
                    $this->command->error("⚠️ Safety Break: Lebih dari 50.000 orders!");
                    break;
                }
            }

            $this->command->info("✅ {$startDate->format('F')}: {$orderCount} Orders");
            $this->command->info("💰 Income: Rp " . number_format($totalIncome));
            $this->command->info("📊 Profit: Rp " . number_format($totalProfit));
            $this->command->info("---");
        }

        $totalOrders = Order::count();
        $this->command->info("🎉 SEEDING SELESAI!");
        $this->command->info("📦 Total Orders: " . number_format($totalOrders));
    }
}

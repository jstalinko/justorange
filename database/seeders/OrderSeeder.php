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

        Order::truncate();

        // Target per bulan
        $TARGET_INCOME = 700_000_000;
        $TARGET_PROFIT = 50_000_000;

        $year = now()->year;

        for ($month = 1; $month <= 11; $month++) {

            $startDate = Carbon::create($year, $month, 1, 0, 0, 0);

            // November hanya sampai tanggal 28
            if ($month === 11) {
                $endDate = Carbon::create($year, 11, 28, 23, 59, 59);
            } else {
                $endDate = Carbon::create($year, $month, 1)->endOfMonth();
            }

            $this->command->info("Generating {$startDate->format('F Y')} ...");

            $totalIncome = 0;
            $totalProfit = 0;
            $orderCount  = 0;

            // Gunakan AND supaya tidak endless loop
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

                if ($orderCount > 30000) {
                    $this->command->error("⚠️ Safety Break Loop!");
                    break;
                }
            }

            $this->command->info("✔ {$startDate->format('F')}: {$orderCount} Orders");
            $this->command->info("Income: " . number_format($totalIncome));
            $this->command->info("Profit: " . number_format($totalProfit));
        }

        $this->command->info("🎉 DONE: SEEDER JAN – NOV BERHASIL JALAN!");
    }
}

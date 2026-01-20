<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class Order2Seeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        if ($products->count() === 0) {
            $this->command->warn("⚠️ Jalankan ProductSeeder dulu!");
            return;
        }

        // Opsional: Hapus komentar jika ingin membersihkan data lama
        // Order::truncate();

        $year = 2025;
        
        // Definisi target profit per bulan
        $monthlyTargets = [
            11 => [
                'name' => 'November',
                'min_profit' => 40_000_000,
                'max_profit' => 45_000_000,
            ],
            12 => [
                'name' => 'Desember',
                'min_profit' => 65_000_000,
                'max_profit' => 70_000_000,
            ],
        ];

        foreach ($monthlyTargets as $month => $target) {
            $targetProfit = rand($target['min_profit'], $target['max_profit']);
            
            $startDate = Carbon::create($year, $month, 1, 0, 0, 0);
            $endDate = Carbon::create($year, $month, 1)->endOfMonth();

            $this->command->info("🔄 Generating {$target['name']} {$year} ...");
            $this->command->info("Target Profit: Rp " . number_format($targetProfit));

            $currentMonthProfit = 0;
            $currentMonthIncome = 0;
            $orderCount = 0;

            // List untuk bulk insert agar lebih cepat
            $data = [];

            while ($currentMonthProfit < $targetProfit) {
                $product = $products->random();
                
                $profitPerItem = $product->price - $product->base_price;

                // Pastikan produk memiliki profit positif agar tidak terjadi infinite loop
                if ($profitPerItem <= 0) continue;

                $randomTimestamp = rand($startDate->timestamp, $endDate->timestamp);

                $data[] = [
                    'product_id'     => $product->id,
                    'uid'            => rand(100000, 999999),
                    'zone_id'        => rand(1000, 9999),
                    'price'          => $product->price,
                    'status'         => 'success',
                    'transaction_id' => 'TRX-' . strtoupper(uniqid()),
                    'created_at'     => Carbon::createFromTimestamp($randomTimestamp),
                    'updated_at'     => Carbon::createFromTimestamp($randomTimestamp),
                ];

                $currentMonthProfit += $profitPerItem;
                $currentMonthIncome += $product->price;
                $orderCount++;

                // Insert setiap 500 data agar memory tidak penuh
                if (count($data) >= 500) {
                    Order::insert($data);
                    $data = [];
                }

                if ($orderCount > 100000) { // Safety break ditingkatkan
                    $this->command->error("⚠️ Safety Break: Limit 100k orders tercapai!");
                    break;
                }
            }

            // Insert sisa data
            if (count($data) > 0) {
                Order::insert($data);
            }

            $this->command->info("✅ {$target['name']}: {$orderCount} Orders");
            $this->command->info("💰 Income: Rp " . number_format($currentMonthIncome));
            $this->command->info("📊 Realized Profit: Rp " . number_format($currentMonthProfit));
            $this->command->info("---");
        }

        $this->command->info("🎉 SEEDING NOVEMBER & DESEMBER 2025 SELESAI!");
    }
}
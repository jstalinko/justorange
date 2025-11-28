<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
    
        Report::truncate();

        // Ambil semua tanggal order
        $dates = Order::selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->pluck('date');

        if ($dates->count() === 0) {
            $this->command->warn("⚠️ Tidak ada data Order, jalankan OrderSeeder dulu");
            return;
        }

        foreach ($dates as $date) {

            $orders = Order::whereDate('created_at', $date)
                ->where('status', 'success')
                ->get();

            if ($orders->count() === 0) {
                continue;
            }

            $totalIncome = $orders->sum('price');
            $totalProfit = $orders->sum(function ($order) {
                return $order->price - ($order->product?->base_price ?? 0);
            });

            Report::create([
                'date'         => $date,
                'total_orders' => $orders->count(),
                'total_income' => $totalIncome,
                'total_profit' => $totalProfit,
            ]);
        }

        $this->command->info("🎉 ReportSeeder selesai! Report harian berhasil dibuat.");
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Product;
use App\Models\Report;
use Carbon\Carbon;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil tanggal unik dari orders
        $dates = Order::selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->pluck('date');

        foreach ($dates as $date) {

            $orders = Order::whereDate('created_at', $date)
                ->where('status', 'success')
                ->get();

            $totalIncome = $orders->sum('price');

            // Profit = harga_jual - harga_modal
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
    }
}

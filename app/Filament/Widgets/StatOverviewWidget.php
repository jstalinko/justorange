<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected function getStats(): array
    {
        return [
            Stat::make('Total Orders', Report::sum('total_orders')),
            Stat::make('Total Income', 'Rp ' . number_format(Report::sum('total_income'), 0, ',', '.')),
            Stat::make('Total Profit', 'Rp ' . number_format(Report::sum('total_profit'), 0, ',', '.')),
        ];
    }
}

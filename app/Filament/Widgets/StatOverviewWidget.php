<?php

namespace App\Filament\Widgets;

use App\Models\Link;
use App\Models\Logs;
use App\Models\User;
use App\Models\Domain;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Links' , Link::count()),
            Stat::make('Users' , User::count()),
            Stat::make('Domains' , Domain::count()),
            Stat::make('Visitors' , Logs::count())
        ];
    }
}

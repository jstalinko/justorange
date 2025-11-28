<?php

namespace App\Filament\Resources\ReportResource\Widgets;

use App\Models\Report;
use Filament\Widgets\Widget;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Carbon\Carbon;

class ReportStatsOverview extends Widget implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.widgets.report-stats-overview';
    protected int|string|array $columnSpan = 'full';

    public ?array $data = [];

   public function mount(): void
{
    $this->form->fill([
        'month' => now()->format('m'),
        'year' => (string) now()->year,
        // ATAU untuk month_year:
        // 'month_year' => now()->format('Y-m'),
    ]);
}

public function form(Forms\Form $form): Forms\Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('month')
                ->label('Bulan')
                ->options([
                    '01' => 'Januari',
                    '02' => 'Februari',
                    '03' => 'Maret',
                    '04' => 'April',
                    '05' => 'Mei',
                    '06' => 'Juni',
                    '07' => 'Juli',
                    '08' => 'Agustus',
                    '09' => 'September',
                    '10' => 'Oktober',
                    '11' => 'November',
                    '12' => 'Desember',
                ])
                ->default(now()->format('m')) // ← Tambahkan ->format('m')
                ->live()
                ->afterStateUpdated(fn () => $this->updateStats()),
                
            Forms\Components\Select::make('year')
                ->label('Tahun')
                ->options(function () {
                    $years = [];
                    $currentYear = now()->year;
                    for ($i = $currentYear - 5; $i <= $currentYear + 1; $i++) {
                        $years[$i] = (string) $i; // ← Cast ke string
                    }
                    return $years;
                })
                ->default((string) now()->year) // ← Cast ke string
                ->live()
                ->afterStateUpdated(fn () => $this->updateStats()),
        ])
        ->columns(2)
        ->statePath('data');
}

   public function getStats(): array
{
    $month = $this->data['month'] ?? now()->format('m');
    $year = $this->data['year'] ?? now()->year;

    $reports = Report::query()
        ->whereRaw("strftime('%Y', date) = ?", [$year])
        ->whereRaw("strftime('%m', date) = ?", [$month])
        ->get();

    $monthName = Carbon::create($year, $month, 1)->format('F Y');

    return [
        [
            'label' => 'Total Order',
            'value' => number_format($reports->sum('total_orders')),
            'description' => "Pesanan bulan {$monthName}",
            'color' => 'primary',
        ],
        [
            'label' => 'Total Income',
            'value' => "Rp " . number_format($reports->sum('total_income')),
            'description' => "Income bulan {$monthName}",
            'color' => 'success',
        ],
        [
            'label' => 'Total Profit',
            'value' => "Rp " . number_format($reports->sum('total_profit')),
            'description' => "Profit bulan {$monthName}",
            'color' => 'warning',
        ],
    ];
}
    public function updateStats(): void
    {
        // Trigger re-render
    }
}
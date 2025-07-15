<?php

namespace App\Filament\Widgets;

use App\Models\Link;
use App\Models\Logs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class VisitorChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Visitor Overview';
    protected static ?int $sort = 1;
    protected string|int|array $columnSpan = 'full';

    /**
     * Properti publik ini akan menyimpan value dari filter yang dipilih.
     * Filament secara otomatis menghubungkan dropdown filter ke properti ini.
     * Kita set default-nya ke 'all' untuk menampilkan semua link.
     */
    public ?string $filter = 'all';

    /**
     * getFilters() untuk Chart Widget seharusnya hanya mengembalikan array key-value sederhana.
     * Key adalah value yang akan disimpan di properti $filter, dan value adalah label yang tampil di dropdown.
     */
    protected function getFilters(): ?array
    {
        // Ambil data link dari database.
        // Key-nya adalah ID link, dan value-nya adalah slug yang akan ditampilkan.
        $links = Link::query()->pluck('slug', 'id')->all();

        // Gabungkan opsi 'All Links' di awal array.
        return ['all' => 'All Links'] + $links;
    }

    protected function getData(): array
    {
        // Ambil nilai filter yang aktif dari properti publik $this->filter.
        $linkId = $this->filter;

        $baseQuery = Logs::query();

        // Terapkan filter HANYA jika yang dipilih bukan 'all'.
        if ($linkId && $linkId !== 'all') {
            $baseQuery->where('link_id', $linkId);
        }

        // Sisa logika di bawah ini sudah benar dan tidak perlu diubah.
        // Ini akan mengambil data berdasarkan query yang sudah difilter di atas.
        $dates = (clone $baseQuery)
            ->selectRaw('DATE(created_at) as date')
            ->distinct()
            ->orderBy('date')
            ->pluck('date')
            ->map(fn ($date) => Carbon::parse($date));

        if ($dates->isEmpty()) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $allLogsData = (clone $baseQuery)
            ->selectRaw('DATE(created_at) as date, type, COUNT(*) as total')
            ->groupBy('date', 'type')
            ->get()
            ->groupBy('date');

        $allVisitors = array_fill_keys($dates->map->toDateString()->all(), 0);
        $allowedVisitors = array_fill_keys($dates->map->toDateString()->all(), 0);
        $blockedVisitors = array_fill_keys($dates->map->toDateString()->all(), 0);

        foreach ($allLogsData as $date => $logsOnDate) {
            foreach ($logsOnDate as $log) {
                if ($log->type === 'allow') {
                    $allowedVisitors[$date] = $log->total;
                } elseif ($log->type === 'block') {
                    $blockedVisitors[$date] = $log->total;
                }
                $allVisitors[$date] = ($allVisitors[$date] ?? 0) + $log->total;
            }
        }

        $labels = $dates->map(fn (Carbon $date) => $date->format('M d'))->all();

        return [
            'datasets' => [
                [
                    'label' => 'All Visitors',
                    'data' => array_values($allVisitors),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.6)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                ],
                [
                    'label' => 'Allowed Visitors',
                    'data' => array_values($allowedVisitors),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.6)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                ],
                [
                    'label' => 'Blocked Visitors',
                    'data' => array_values($blockedVisitors),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.6)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}

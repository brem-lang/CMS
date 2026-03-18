<?php

namespace App\Filament\Widgets;

use App\Models\Visit;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class VisitsChartWidget extends ChartWidget
{
    protected ?string $heading = 'Unique Visitors';

    protected static ?int $sort = 6;

    public ?string $filter = '7days';

    protected function getFilters(): ?array
    {
        return [
            '7days' => 'Last 7 days',
            '30days' => 'Last 30 days',
            'month' => 'This Month',
            'year' => 'This Year',
        ];
    }

    protected function getData(): array
    {
        $startDate = match ($this->filter) {
            '7days' => now()->subDays(7)->startOfDay(),
            '30days' => now()->subDays(30)->startOfDay(),
            'month' => now()->startOfMonth(),
            'year' => now()->startOfYear(),
            default => now()->subDays(7)->startOfDay(),
        };

        $endDate = now()->endOfDay();

        $visitsData = Visit::whereBetween('visited_at', [$startDate, $endDate])
            ->select(
                DB::raw('visited_at as date'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('visited_at')
            ->orderBy('visited_at')
            ->get();

        $labels = [];
        $count = [];

        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('M d');

            $dayData = $visitsData->first(fn ($row) => (string) $row->date === $dateStr);

            $count[] = $dayData ? (int) $dayData->count : 0;

            $currentDate->addDay();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Unique Visitors',
                    'data' => $count,
                    'backgroundColor' => 'rgba(99, 102, 241, 0.5)',
                    'borderColor' => 'rgb(99, 102, 241)',
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Visitors',
                    ],
                    'beginAtZero' => true,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
        ];
    }
}

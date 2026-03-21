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
                DB::raw('device_type'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('visited_at', 'device_type')
            ->orderBy('visited_at')
            ->get();

        $labels = [];
        $mobileCount = [];
        $tabletCount = [];
        $desktopCount = [];

        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('M d');

            $dayData = $visitsData->filter(fn ($row) => (string) $row->date === $dateStr);
            $mobileCount[] = (int) ($dayData->firstWhere('device_type', 'mobile')?->count ?? 0);
            $tabletCount[] = (int) ($dayData->firstWhere('device_type', 'tablet')?->count ?? 0);
            $desktopCount[] = (int) ($dayData->firstWhere('device_type', 'desktop')?->count ?? 0);

            $currentDate->addDay();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Mobile',
                    'data' => $mobileCount,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'fill' => true,
                ],
                [
                    'label' => 'Tablet',
                    'data' => $tabletCount,
                    'backgroundColor' => 'rgba(251, 191, 36, 0.5)',
                    'borderColor' => 'rgb(251, 191, 36)',
                    'fill' => true,
                ],
                [
                    'label' => 'Desktop',
                    'data' => $desktopCount,
                    'backgroundColor' => 'rgba(107, 114, 128, 0.5)',
                    'borderColor' => 'rgb(107, 114, 128)',
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

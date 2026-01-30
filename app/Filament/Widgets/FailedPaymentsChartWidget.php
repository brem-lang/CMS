<?php

namespace App\Filament\Widgets;

use App\Models\FailedPayment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class FailedPaymentsChartWidget extends ChartWidget
{
    protected ?string $heading = 'Failed Payments Analysis';

    protected static ?int $sort = 4;

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

        // Get failed payments grouped by date
        $failedPaymentsData = FailedPayment::whereBetween('failed_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(failed_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as total_amount')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Prepare labels and datasets
        $labels = [];
        $count = [];
        $amount = [];

        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('M d');
            
            $dayData = $failedPaymentsData->firstWhere('date', $dateStr);
            $count[] = $dayData ? $dayData->count : 0;
            $amount[] = $dayData ? (float) $dayData->total_amount : 0;

            $currentDate->addDay();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Failed Payments Count',
                    'data' => $count,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.5)',
                    'borderColor' => 'rgb(239, 68, 68)',
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Failed Amount (₱)',
                    'data' => $amount,
                    'backgroundColor' => 'rgba(251, 191, 36, 0.5)',
                    'borderColor' => 'rgb(251, 191, 36)',
                    'yAxisID' => 'y1',
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
                    'position' => 'left',
                    'title' => [
                        'display' => true,
                        'text' => 'Count',
                    ],
                    'beginAtZero' => true,
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => 'Amount (₱)',
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
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

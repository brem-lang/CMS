<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class OrdersRevenueChartWidget extends ChartWidget
{
    protected ?string $heading = 'Orders & Revenue';

    protected static ?int $sort = 2;

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

        // Get orders grouped by date
        $ordersData = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Prepare labels and datasets
        $labels = [];
        $ordersCount = [];
        $revenue = [];

        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dateStr = $currentDate->format('Y-m-d');
            $labels[] = $currentDate->format('M d');
            
            $dayData = $ordersData->firstWhere('date', $dateStr);
            $ordersCount[] = $dayData ? $dayData->orders_count : 0;
            $revenue[] = $dayData ? (float) $dayData->revenue : 0;

            $currentDate->addDay();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $ordersCount,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Revenue (₱)',
                    'data' => $revenue,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)',
                    'borderColor' => 'rgb(16, 185, 129)',
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
                        'text' => 'Orders',
                    ],
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => 'Revenue (₱)',
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
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

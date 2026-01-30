<?php

namespace App\Filament\Widgets;

use App\Models\OrderItem;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ProductSalesChartWidget extends ChartWidget
{
    protected ?string $heading = 'Product Sales Performance';

    protected static ?int $sort = 5;

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

        // Get top selling products
        $productSalesData = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.payment_status', 'paid')
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        $labels = [];
        $quantities = [];
        $revenues = [];

        foreach ($productSalesData as $product) {
            $labels[] = strlen($product->name) > 20 ? substr($product->name, 0, 20) . '...' : $product->name;
            $quantities[] = (int) $product->total_quantity;
            $revenues[] = (float) $product->total_revenue;
        }

        // If no data, return empty chart
        if (empty($labels)) {
            return [
                'datasets' => [
                    [
                        'label' => 'Quantity Sold',
                        'data' => [0],
                        'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                        'borderColor' => 'rgb(59, 130, 246)',
                    ],
                ],
                'labels' => ['No Data'],
            ];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Quantity Sold',
                    'data' => $quantities,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'xAxisID' => 'x',
                ],
                [
                    'label' => 'Revenue (₱)',
                    'data' => $revenues,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)',
                    'borderColor' => 'rgb(16, 185, 129)',
                    'xAxisID' => 'x1',
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
            'indexAxis' => 'y',
            'scales' => [
                'x' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'bottom',
                    'title' => [
                        'display' => true,
                        'text' => 'Quantity',
                    ],
                    'beginAtZero' => true,
                ],
                'x1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'top',
                    'title' => [
                        'display' => true,
                        'text' => 'Revenue (₱)',
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

<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class DashboardStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // Orders data
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $paidOrders = Order::where('payment_status', 'paid')->count();
        
        // Get orders count for the last 7 days for chart
        $ordersLast7Days = Order::where('created_at', '>=', now()->subDays(7))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count')
            ->toArray();
        
        // Pad array to 7 elements if needed
        while (count($ordersLast7Days) < 7) {
            array_unshift($ordersLast7Days, 0);
        }
        
        // Stocks data
        $totalStocks = Product::sum('stock_quantity');
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock_quantity', '<', 10)->count();
        $outOfStockProducts = Product::where('stock_quantity', '<=', 0)->count();
        
        return [
            Stat::make('Total Orders', number_format($totalOrders))
                ->description($pendingOrders . ' pending orders')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('success')
                ->chart(count($ordersLast7Days) > 0 ? $ordersLast7Days : [0, 0, 0, 0, 0, 0, 0]),
            Stat::make('Paid Orders', number_format($paidOrders))
                ->description(number_format(($totalOrders > 0 ? ($paidOrders / $totalOrders) * 100 : 0), 1) . '% of total')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Total Stocks', number_format($totalStocks))
                ->description($totalProducts . ' products in inventory')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),
            Stat::make('Low Stock Alert', $lowStockProducts)
                ->description($outOfStockProducts . ' out of stock')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStockProducts > 0 ? 'warning' : 'success'),
        ];
    }
}

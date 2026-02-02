<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),

            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'pending')
                ),

            'processing' => Tab::make('Processing')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'processing')
                ),

            'confirm' => Tab::make('Order Confirmed')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'confirm')
                ),

            'shipped' => Tab::make('Shipped')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'shipped')
                ),

            'delivered' => Tab::make('Complete')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'delivered')
                ),

            'cancelled' => Tab::make('Cancelled')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'cancelled')
                ),
        ];
    }
}

<?php

namespace App\Filament\Resources\FailedPayments\Pages;

use App\Filament\Resources\FailedPayments\FailedPaymentResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListFailedPayments extends ListRecords
{
    protected static string $resource = FailedPaymentResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),

            'recent' => Tab::make('Recent')
                ->modifyQueryUsing(
                    fn (Builder $query) => $query->where('failed_at', '>=', now()->subDays(7))
                ),

            'this_month' => Tab::make('This Month')
                ->modifyQueryUsing(
                    fn (Builder $query) => $query->whereMonth('failed_at', now()->month)
                        ->whereYear('failed_at', now()->year)
                ),
        ];
    }
}

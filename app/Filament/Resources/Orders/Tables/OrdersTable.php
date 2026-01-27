<?php

namespace App\Filament\Resources\Orders\Tables;

use App\Models\Courier;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order Number')
                    ->weight('bold')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->default('Guest'),
                TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('PHP')
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->label('Payment Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        'cancelled' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Order Status')
                    ->badge()
                    ->formatStateUsing(function (string $state, $record): string {
                        $formatted = match ($state) {
                            'delivered' => 'Complete',
                            'confirm' => 'Order Confirmed',
                            default => ucfirst($state),
                        };
                        
                        // Append courier if status is shipped and courier exists
                        if ($state === 'shipped' && $record && $record->courier) {
                            $formatted .= ' (' . $record->courier->name . ')';
                        }
                        
                        return $formatted;
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'delivered' => 'success',
                        'shipped' => 'info',
                        'confirm' => 'success',
                        'pending' => 'gray',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable()
                    ->action(
                        fn ($record) => Action::make('updateStatus')
                            ->form([
                                Select::make('status')
                                    ->label('Order Status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'confirm' => 'Order Confirmed',
                                        'shipped' => 'Shipped',
                                        'delivered' => 'Complete',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->required()
                                    ->default($record->status)
                                    ->live(),
                                Select::make('courier_id')
                                    ->label('Courier')
                                    ->options(fn () => Courier::query()->pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required(fn ($get) => $get('status') === 'shipped')
                                    ->visible(fn ($get) => $get('status') === 'shipped')
                                    ->default($record->courier_id)
                                    ->placeholder('Select courier'),
                            ])
                            ->action(function (array $data) use ($record) {
                                $record->update([
                                    'status' => $data['status'],
                                    'courier_id' => $data['status'] === 'shipped' ? ($data['courier_id'] ?? null) : null,
                                ]);
                            })
                            ->successNotificationTitle('Order status updated successfully')
                    ),
                TextColumn::make('courier.name')
                    ->label('Courier')
                    ->badge()
                    ->visible(fn ($record) => $record && $record->status === 'shipped' && $record->courier)
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state)))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('status')
                    ->label('Order Status')
                    ->options([
                        'pending' => 'Pending',
                        'confirm' => 'Order Confirmed',
                        'shipped' => 'Shipped',
                        'delivered' => 'Complete',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('courier_id')
                    ->label('Courier')
                    ->options(fn () => Courier::query()->pluck('name', 'id')),
                SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'gcash' => 'GCash',
                        'paymaya' => 'PayMaya',
                        'bank_transfer' => 'Bank Transfer',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}

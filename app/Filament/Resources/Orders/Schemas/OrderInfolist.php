<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Information')
                    ->schema([
                        TextEntry::make('order_number')
                            ->label('Order Number')
                            ->weight('bold')
                            ->size('lg'),
                        TextEntry::make('user.name')
                            ->label('Customer')
                            ->default('Guest'),
                        TextEntry::make('created_at')
                            ->label('Order Date')
                            ->dateTime(),
                    ])
                    ->columns(3),
                Section::make('Customer Information')
                    ->schema([
                        TextEntry::make('email')
                            ->label('Email'),
                        TextEntry::make('full_name')
                            ->label('Full Name'),
                        TextEntry::make('phone')
                            ->label('Phone'),
                    ])
                    ->columns(3),
                Section::make('Shipping Address')
                    ->schema([
                        TextEntry::make('address')
                            ->label('Address')
                            ->columnSpanFull(),
                        TextEntry::make('town')
                            ->label('Town/City'),
                        TextEntry::make('state')
                            ->label('State/Province'),
                        TextEntry::make('postcode')
                            ->label('Postal Code'),
                        TextEntry::make('country')
                            ->label('Country'),
                    ])
                    ->columns(4),
                Section::make('Order Details')
                    ->schema([
                        TextEntry::make('subtotal')
                            ->label('Subtotal')
                            ->money('PHP'),
                        TextEntry::make('total')
                            ->label('Total')
                            ->money('PHP')
                            ->weight('bold')
                            ->size('lg'),
                        TextEntry::make('payment_method')
                            ->label('Payment Method')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state))),
                        TextEntry::make('payment_status')
                            ->label('Payment Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'paid' => 'success',
                                'pending' => 'warning',
                                'failed' => 'danger',
                                'cancelled' => 'gray',
                                default => 'gray',
                            }),
                        TextEntry::make('status')
                            ->label('Order Status')
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'delivered' => 'Complete',
                                'confirm' => 'Order Confirmed',
                                default => ucfirst($state),
                            })
                            ->color(fn (string $state): string => match ($state) {
                                'delivered' => 'success',
                                'shipped' => 'info',
                                'confirm' => 'success',
                                'pending' => 'gray',
                                'cancelled' => 'danger',
                                default => 'gray',
                            }),
                        TextEntry::make('courier.name')
                            ->label('Courier')
                            ->badge()
                            ->visible(fn ($record) => $record && $record->status === 'shipped' && $record->courier)
                            ->placeholder('-'),
                        TextEntry::make('payment_intent_id')
                            ->label('Payment Intent ID')
                            ->placeholder('-'),
                        TextEntry::make('payment_source_id')
                            ->label('Payment Source ID')
                            ->placeholder('-'),
                        TextEntry::make('order_notes')
                            ->label('Order Notes')
                            ->placeholder('No notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Section::make('Order Items')
                    ->schema([
                        RepeatableEntry::make('orderItems')
                            ->label('Items')
                            ->schema([
                                TextEntry::make('product.name')
                                    ->label('Product')
                                    ->weight('bold')
                                    ->placeholder('Product not found'),
                                TextEntry::make('quantity')
                                    ->label('Quantity')
                                    ->numeric(),
                                TextEntry::make('price')
                                    ->label('Unit Price')
                                    ->money('PHP'),
                                TextEntry::make('subtotal')
                                    ->label('Subtotal')
                                    ->money('PHP')
                                    ->weight('bold'),
                            ])
                            ->columns(4)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }
}

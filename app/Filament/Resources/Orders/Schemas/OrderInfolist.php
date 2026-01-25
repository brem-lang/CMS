<?php

namespace App\Filament\Resources\Orders\Schemas;

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
                        TextEntry::make('courier')
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
                        TextEntry::make('items')
                            ->label('Items')
                            ->formatStateUsing(function ($state) {
                                if (empty($state)) {
                                    return 'No items';
                                }
                                
                                $items = is_string($state) ? json_decode($state, true) : $state;
                                if (!is_array($items)) {
                                    return 'No items';
                                }
                                
                                $html = '<div class="space-y-2">';
                                foreach ($items as $item) {
                                    $name = $item['name'] ?? 'Unknown';
                                    $quantity = $item['quantity'] ?? 0;
                                    $price = $item['price'] ?? 0;
                                    $subtotal = $item['subtotal'] ?? ($quantity * $price);
                                    
                                    $html .= '<div class="flex justify-between items-center p-2 bg-gray-50 rounded">';
                                    $html .= '<div>';
                                    $html .= '<span class="font-medium">' . htmlspecialchars($name) . '</span>';
                                    $html .= '<span class="text-gray-500 ml-2">x' . $quantity . '</span>';
                                    $html .= '</div>';
                                    $html .= '<div class="font-medium">₱' . number_format($subtotal, 2) . '</div>';
                                    $html .= '</div>';
                                }
                                $html .= '</div>';
                                
                                return new \Illuminate\Support\HtmlString($html);
                            })
                            ->html()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

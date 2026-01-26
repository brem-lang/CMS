<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Information')
                    ->schema([
                        TextInput::make('order_number')
                            ->label('Order Number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabled(fn ($record) => $record !== null),
                        Select::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ])
                    ->columns(2),
                Section::make('Customer Information')
                    ->schema([
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required(),
                        TextInput::make('full_name')
                            ->label('Full Name')
                            ->required(),
                        TextInput::make('phone')
                            ->label('Phone')
                            ->required(),
                    ])
                    ->columns(3),
                Section::make('Shipping Address')
                    ->schema([
                        Textarea::make('address')
                            ->label('Address')
                            ->required()
                            ->rows(2)
                            ->columnSpanFull(),
                        TextInput::make('town')
                            ->label('Town/City')
                            ->required(),
                        TextInput::make('state')
                            ->label('State/Province')
                            ->required(),
                        TextInput::make('postcode')
                            ->label('Postal Code')
                            ->required(),
                        TextInput::make('country')
                            ->label('Country')
                            ->default('Philippines')
                            ->required(),
                    ])
                    ->columns(3),
                Section::make('Order Details')
                    ->schema([
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->prefix('₱')
                            ->required(),
                        TextInput::make('total')
                            ->label('Total')
                            ->numeric()
                            ->prefix('₱')
                            ->required(),
                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'gcash' => 'GCash',
                                'paymaya' => 'PayMaya',
                                'bank_transfer' => 'Bank Transfer',
                            ])
                            ->required(),
                        Select::make('payment_status')
                            ->label('Payment Status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
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
                            ->live(),
                        Select::make('courier_id')
                            ->label('Courier')
                            ->relationship('courier', 'name')
                            ->searchable()
                            ->preload()
                            ->required(fn ($get) => $get('status') === 'shipped')
                            ->visible(fn ($get) => $get('status') === 'shipped')
                            ->placeholder('Select courier')
                            ->nullable()
                            ->dehydrated(fn ($get) => $get('status') === 'shipped'),
                        TextInput::make('payment_intent_id')
                            ->label('Payment Intent ID')
                            ->nullable(),
                        TextInput::make('payment_source_id')
                            ->label('Payment Source ID')
                            ->nullable(),
                        Textarea::make('order_notes')
                            ->label('Order Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}

<?php

namespace App\Filament\Resources\FailedPayments\Schemas;

use App\Models\Courier;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FailedPaymentForm
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
                                'grab_pay' => 'GrabPay',
                            ])
                            ->required(),
                        TextInput::make('payment_status')
                            ->label('Payment Status')
                            ->default('failed')
                            ->disabled()
                            ->dehydrated(),
                        Textarea::make('failure_reason')
                            ->label('Failure Reason')
                            ->rows(2)
                            ->columnSpanFull(),
                        TextInput::make('failed_at')
                            ->label('Failed At')
                            // ->datetime()
                            ->required(),
                        TextInput::make('status')
                            ->label('Order Status')
                            ->default('cancelled')
                            ->disabled()
                            ->dehydrated(),
                        Select::make('courier_id')
                            ->label('Courier')
                            ->options(fn () => Courier::query()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        TextInput::make('payment_intent_id')
                            ->label('Payment Intent ID')
                            ->nullable(),
                        TextInput::make('payment_source_id')
                            ->label('Payment Source ID')
                            ->nullable(),
                        TextInput::make('checkout_session_id')
                            ->label('Checkout Session ID')
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

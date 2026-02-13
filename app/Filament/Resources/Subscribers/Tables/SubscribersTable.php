<?php

namespace App\Filament\Resources\Subscribers\Tables;

use App\Jobs\SendSubscriberDigitalGiftEmails;
use App\Models\DigitalProduct;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class SubscribersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Subscribed')
                    ->dateTime('F j, Y')
                    ->sortable(),
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('sendEmail')
                        ->label('Send Email')
                        ->icon('heroicon-o-inbox-arrow-down')
                        ->requiresConfirmation()
                        ->schema([
                            Textarea::make('message')
                                ->required()
                                ->label('Message'),
                            Select::make('digital_product_id')
                                ->label('Digital Product')
                                ->options(fn () => DigitalProduct::where('is_free', true)->where('for_subscribers', true)->pluck('title', 'id'))
                                ->searchable()
                                ->preload()
                                ->required()
                                ->placeholder('Select digital product'),
                        ])
                        ->action(function (Collection $records, array $data) {
                            $records->chunk(100)->each(function ($chunk) use ($data) {
                                foreach ($chunk as $subscriber) {
                                    SendSubscriberDigitalGiftEmails::dispatch(
                                        $subscriber,
                                        (int) $data['digital_product_id'],
                                        (string) ($data['message'] ?? '')
                                    );

                                    sleep(15);
                                }
                            });
                        })
                        ->successNotificationTitle(fn (Collection $records) => 'Emails are being sent to '.$records->count().' subscriber(s).'),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}

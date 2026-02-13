<?php

namespace App\Filament\Resources\DigitalProducts\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;

class DigitalProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Product Information')
                    ->schema([
                        TextEntry::make('thumbnail')
                            ->label('Thumbnail')
                            ->html()
                            ->formatStateUsing(function ($state) {
                                $url = $state ? Storage::disk('public')->url($state) : url('bootstrap/img/product/product-1.jpg');
                                return '<img src="' . $url . '" alt="Thumbnail" style="max-width: 200px; height: auto; border-radius: 8px;" />';
                            })
                            ->columnSpanFull(),
                        TextEntry::make('title')
                            ->label('Title')
                            ->weight('bold'),
                        TextEntry::make('description')
                            ->label('Description')
                            ->columnSpanFull(),
                        TextEntry::make('file_type')
                            ->label('File Type')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pdf' => 'info',
                                'audio' => 'success',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => strtoupper($state)),
                        TextEntry::make('is_free')
                            ->label('Pricing Type')
                            ->badge()
                            ->color(fn (bool $state): string => $state ? 'success' : 'warning')
                            ->formatStateUsing(fn (bool $state): string => $state ? 'Free' : 'Paid'),
                        TextEntry::make('price')
                            ->label('Price')
                            ->formatStateUsing(fn ($state, $record) => $record->is_free ? 'Free' : '₱' . number_format($state, 2)),
                        IconEntry::make('for_subscribers')
                            ->label('For Subscribers')
                            ->boolean(),
                        IconEntry::make('is_active')
                            ->label('Active Status')
                            ->boolean(),
                        TextEntry::make('file_path')
                            ->label('File Path')
                            ->copyable()
                            ->copyMessage('File path copied!')
                            ->copyMessageDuration(1500),
                        TextEntry::make('addedBy.name')
                            ->label('Added By'),
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->dateTime(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ])
            ->columns(1);
    }
}

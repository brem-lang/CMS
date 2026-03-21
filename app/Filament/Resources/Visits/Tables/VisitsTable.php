<?php

namespace App\Filament\Resources\Visits\Tables;

use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VisitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('url')
                    ->label('URL')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->url)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('device_type')
                    ->label('Device')
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'mobile' => 'info',
                        'tablet' => 'warning',
                        'desktop' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('visited_at')
                    ->label('Visited At')
                    ->date('F j, Y')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Recorded')
                    ->dateTime('F j, Y g:i A')
                    ->sortable(),
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->defaultSort('visited_at', 'desc');
    }
}

<?php

namespace App\Filament\Resources\MyContents\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MyContentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->placeholder('Untitled')
                    ->limit(40),
                TextColumn::make('video_path')
                    ->label('Video')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('highlights')
                    ->label('Highlights')
                    ->badge()
                    ->formatStateUsing(fn(bool $state): string => $state ? 'Yes' : 'No')
                    ->color(fn(bool $state): string => $state ? 'success' : 'gray'),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('F j, Y g:i A')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('sort_order', 'asc');
    }
}

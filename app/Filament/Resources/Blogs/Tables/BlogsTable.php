<?php

namespace App\Filament\Resources\Blogs\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BlogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Created By')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime('M d, Y H:i'),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state ? 'Published' : 'Draft')
                    ->color(fn ($state) => match ($state) {
                        true => 'success',
                        default => 'danger',
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([]);
    }
}

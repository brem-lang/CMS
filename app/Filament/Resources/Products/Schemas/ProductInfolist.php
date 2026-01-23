<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextEntry::make('name')
                            ->weight('bold'),
                        TextEntry::make('description'),
                        TextEntry::make('addedBy.name'),
                        TextEntry::make('price')
                            ->money('PHP'),
                        TextEntry::make('stock_quantity')
                            ->numeric(),
                        IconEntry::make('status')
                            ->boolean(),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Blogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BlogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextEntry::make('title')
                            ->weight('bold'),
                        TextEntry::make('content')
                            ->columnSpanFull()
                            ->html(),
                        TextEntry::make('user.name')
                            ->label('Created By'),
                        TextEntry::make('created_at')
                            ->dateTime('M d, Y H:i'),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}

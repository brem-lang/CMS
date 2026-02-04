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
                            ->numeric()
                            ->formatStateUsing(fn ($state) => $state == 0 ? 'Out of Stock' : $state)
                            ->color(fn ($state) => $state == 0 ? 'danger' : null)
                            ->weight(fn ($state) => $state == 0 ? 'bold' : null),
                        IconEntry::make('status')
                            ->boolean(),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Product Options')
                    ->description('Size and color options for this product')
                    ->schema([
                        IconEntry::make('has_size_options')
                            ->label('Size Options Enabled')
                            ->boolean(),
                        TextEntry::make('size_options')
                            ->label('Available Sizes')
                            ->formatStateUsing(function ($state, $record) {
                                // Handle case when size options are disabled
                                if (!$record->has_size_options) {
                                    return 'Size options are disabled';
                                }
                                
                                // Handle null or empty state
                                if (empty($state)) {
                                    return 'No size options configured';
                                }
                                
                                // If state is a JSON string, decode it
                                if (is_string($state)) {
                                    $decoded = json_decode($state, true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                        $state = $decoded;
                                    } else {
                                        return 'Invalid size options data';
                                    }
                                }
                                
                                // Ensure state is an array
                                if (!is_array($state)) {
                                    return 'No size options configured';
                                }
                                
                                // Extract names from the array
                                $names = [];
                                foreach ($state as $item) {
                                    if (is_array($item) && isset($item['name'])) {
                                        $names[] = $item['name'];
                                    } elseif (is_string($item)) {
                                        $names[] = $item;
                                    }
                                }
                                
                                // Filter out empty names
                                $names = array_filter($names);
                                
                                if (empty($names)) {
                                    return 'No size options configured';
                                }
                                
                                return implode(', ', $names);
                            })
                            ->placeholder('No size options')
                            ->columnSpanFull(),
                        IconEntry::make('has_color_options')
                            ->label('Color Options Enabled')
                            ->boolean(),
                        TextEntry::make('color_options')
                            ->label('Available Colors')
                            ->formatStateUsing(function ($state, $record) {
                                // Handle case when color options are disabled
                                if (!$record->has_color_options) {
                                    return 'Color options are disabled';
                                }
                                
                                // Handle null or empty state
                                if (empty($state)) {
                                    return 'No color options configured';
                                }
                                
                                // If state is a JSON string, decode it
                                if (is_string($state)) {
                                    $decoded = json_decode($state, true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                        $state = $decoded;
                                    } else {
                                        return 'Invalid color options data';
                                    }
                                }
                                
                                // Ensure state is an array
                                if (!is_array($state)) {
                                    return 'No color options configured';
                                }
                                
                                // Extract names from the array
                                $names = [];
                                foreach ($state as $item) {
                                    if (is_array($item) && isset($item['name'])) {
                                        $names[] = $item['name'];
                                    } elseif (is_string($item)) {
                                        $names[] = $item;
                                    }
                                }
                                
                                // Filter out empty names
                                $names = array_filter($names);
                                
                                if (empty($names)) {
                                    return 'No color options configured';
                                }
                                
                                return implode(', ', $names);
                            })
                            ->placeholder('No color options')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->collapsed(fn ($record) => !($record->has_size_options || $record->has_color_options)),
            ]);
    }
}

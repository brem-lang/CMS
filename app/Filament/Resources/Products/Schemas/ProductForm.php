<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->columnSpanFull()
                            ->required(),
                        Textarea::make('description')
                            ->columnSpanFull()
                            ->required(),
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('₱'),
                        TextInput::make('stock_quantity')
                            ->required()
                            ->numeric(),
                    ])
                    ->columns(2)
                    ->columnSpan(2),
                Section::make()
                    ->schema([
                        FileUpload::make('image')
                            ->required()
                            ->disk('public')
                            ->directory('products')
                            ->image()
                            ->maxSize(5120),
                        FileUpload::make('additional_images')
                            ->label('Additional Images')
                            ->disk('public')
                            ->directory('products/additional')
                            ->image()
                            ->multiple()
                            ->maxSize(5120)
                            ->maxFiles(10)
                            ->helperText('Optional: Upload multiple additional images for this product'),
                        Toggle::make('status')
                            ->label('Active')
                            ->onIcon(Heroicon::Check)
                            ->offIcon(Heroicon::XMark)
                            ->inline(false)
                            ->default(true)
                            ->required(),
                    ])
                    ->columnSpan(1),
                Section::make('Product Options')
                    ->description('Configure size and color options for this product')
                    ->schema([
                        Toggle::make('has_size_options')
                            ->label('Enable Size Options')
                            ->onIcon(Heroicon::Check)
                            ->offIcon(Heroicon::XMark)
                            ->inline(false)
                            ->reactive()
                            ->live()
                            ->default(false)
                            ->helperText('Allow customers to select different sizes for this product'),
                        Repeater::make('size_options')
                            ->label('Size Options')
                            ->visible(fn (Get $get) => $get('has_size_options'))
                            ->schema([
                                TextInput::make('name')
                                    ->label('Size Name')
                                    ->required()
                                    ->placeholder('e.g., Small, Medium, Large'),
                            ])
                            ->defaultItems(0)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
                        Toggle::make('has_color_options')
                            ->label('Enable Color Options')
                            ->onIcon(Heroicon::Check)
                            ->offIcon(Heroicon::XMark)
                            ->inline(false)
                            ->reactive()
                            ->live()
                            ->default(false)
                            ->helperText('Allow customers to select different colors for this product'),
                        Repeater::make('color_options')
                            ->label('Color Options')
                            ->visible(fn (Get $get) => $get('has_color_options'))
                            ->schema([
                                TextInput::make('name')
                                    ->label('Color Name')
                                    ->required()
                                    ->placeholder('e.g., Red, Blue, Green'),
                            ])
                            ->defaultItems(0)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
                    ])
                    ->columns(1)
                    ->columnStart(3)
                    ->columnSpan(1),
            ])
            ->columns(3);
    }
}

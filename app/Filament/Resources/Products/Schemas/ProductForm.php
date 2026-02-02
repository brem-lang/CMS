<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
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
                            ->required(),
                        Textarea::make('description')
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
            ])
            ->columns(3);
    }
}

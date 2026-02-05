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
                Section::make('Product Variants')
                    ->description('Add specific variants with quantities (e.g., Black Medium: 10, White Large: 5)')
                    ->schema([
                        Repeater::make('variants')
                            ->label('Variants')
                            ->relationship('variants')
                            ->schema([
                                TextInput::make('color')
                                    ->label('Color')
                                    ->placeholder('e.g., Black, White, Red')
                                    ->maxLength(255),
                                TextInput::make('size')
                                    ->label('Size')
                                    ->placeholder('e.g., Small, Medium, Large')
                                    ->maxLength(255),
                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->helperText('Stock quantity for this variant'),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => 
                                trim(($state['color'] ?? '') . ' ' . ($state['size'] ?? '')) . 
                                ($state['quantity'] ? ' (' . $state['quantity'] . ')' : '')
                            )
                            ->addActionLabel('Add Variant')
                            ->helperText('Add different color and size combinations with their respective quantities. Leave color or size empty if not applicable.'),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
            ])
            ->columns(3);
    }
}

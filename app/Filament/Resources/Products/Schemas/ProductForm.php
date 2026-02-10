<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
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
                    ->columnSpanFull()
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
                        Toggle::make('status')
                            ->label('Active')
                            ->onIcon(Heroicon::Check)
                            ->offIcon(Heroicon::XMark)
                            ->inline(false)
                            ->default(true)
                            ->required(),
                        TextInput::make('stock_quantity')
                            ->label('Stock Quantity')
                            ->required(fn ($get) => !$get('has_variants'))
                            ->numeric()
                            ->helperText(fn ($get) => $get('has_variants') ? 'Stock quantity is calculated from variant quantities' : 'Total stock quantity for this product')
                            ->visible(fn ($get) => !$get('has_variants')),
                        Toggle::make('has_variants')
                            ->label('Has Product Variants')
                            ->onIcon(Heroicon::Check)
                            ->offIcon(Heroicon::XMark)
                            ->inline(false)
                            ->default(false)
                            ->helperText('Enable this to add color and size variants. When enabled, product images will be managed per variant.')
                            ->columnSpanFull()
                            ->live(),
                        FileUpload::make('image')
                            ->label('Product Image')
                            ->disk('public')
                            ->directory('products')
                            ->image()
                            ->maxSize(2048)
                            ->imageEditor()
                            ->visibility('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                            ->required(fn ($get) => !$get('has_variants'))
                            ->helperText('Main product image (required when product has no variants)')
                            ->visible(fn ($get) => !$get('has_variants'))
                            ->columnSpanFull(),
                        FileUpload::make('additional_images')
                            ->label('Additional Images')
                            ->disk('public')
                            ->directory('products/additional')
                            ->image()
                            ->maxSize(2048)
                            ->imageEditor()
                            ->visibility('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                            ->multiple()
                            ->maxFiles(10)
                            ->helperText('Upload additional product images (optional, only for products without variants)')
                            ->visible(fn ($get) => !$get('has_variants'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                Section::make('Product Variants')
                    ->description('Choose variant type and add variants with optional multiple images per variant.')
                    ->visible(fn ($get) => $get('has_variants'))
                    ->schema([
                        Select::make('variant_type')
                            ->label('Variant Type')
                            ->options([
                                'size' => 'Size only',
                                'color' => 'Color only',
                                'both' => 'Size and color',
                            ])
                            ->default('both')
                            ->required()
                            ->live()
                            ->helperText('Size only: one variant per size. Color only: one variant per color. Both: one variant per size + color combination.'),
                        // --- Size only ---
                        Repeater::make('size_variants')
                            ->label('Size Variants')
                            ->schema([
                                TextInput::make('size')
                                    ->label('Size')
                                    ->placeholder('e.g., XS, S, M, L, XL')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),
                                FileUpload::make('images')
                                    ->label('Variant Images')
                                    ->disk('public')
                                    ->directory('products/variants')
                                    ->image()
                                    ->maxSize(2048)
                                    ->imageEditor()
                                    ->visibility('public')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                                    ->multiple()
                                    ->maxFiles(10)
                                    ->helperText('Upload one or more images for this size variant')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add Size Variant')
                            ->itemLabel(fn (array $state): ?string => ($state['size'] ?? 'Size') . (isset($state['quantity']) ? ' (' . $state['quantity'] . ')' : ''))
                            ->collapsible()
                            ->visible(fn ($get) => $get('variant_type') === 'size'),
                        // --- Color only ---
                        Repeater::make('color_only_variants')
                            ->label('Color Variants')
                            ->schema([
                                TextInput::make('color')
                                    ->label('Color')
                                    ->placeholder('e.g., Black, White, Red')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->required()
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0),
                                FileUpload::make('images')
                                    ->label('Variant Images')
                                    ->disk('public')
                                    ->directory('products/variants')
                                    ->image()
                                    ->maxSize(2048)
                                    ->imageEditor()
                                    ->visibility('public')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                                    ->multiple()
                                    ->maxFiles(10)
                                    ->helperText('Upload one or more images for this color variant')
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel('Add Color Variant')
                            ->itemLabel(fn (array $state): ?string => ($state['color'] ?? 'Color') . (isset($state['quantity']) ? ' (' . $state['quantity'] . ')' : ''))
                            ->collapsible()
                            ->visible(fn ($get) => $get('variant_type') === 'color'),
                        // --- Size and color (both) ---
                        Repeater::make('color_variants')
                            ->label('Color Families (with sizes)')
                            ->schema([
                                TextInput::make('color')
                                    ->label('Color Family')
                                    ->placeholder('e.g., Black, White, Red')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),
                                Repeater::make('sizes')
                                    ->label('Sizes & Quantities')
                                    ->schema([
                                        TextInput::make('size')
                                            ->label('Size')
                                            ->placeholder('e.g., XS, S, M, L, XL')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('quantity')
                                            ->label('Quantity')
                                            ->required()
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0),
                                        FileUpload::make('images')
                                            ->label('Variant Images')
                                            ->disk('public')
                                            ->directory('products/variants')
                                            ->image()
                                            ->maxSize(2048)
                                            ->imageEditor()
                                            ->visibility('public')
                                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                                            ->multiple()
                                            ->maxFiles(10)
                                            ->helperText('Images for this size+color')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->defaultItems(0)
                                    ->addActionLabel('Add Size')
                                    ->itemLabel(fn (array $state): ?string => ($state['size'] ?? '') . (isset($state['quantity']) ? ' (' . $state['quantity'] . ')' : ''))
                                    ->collapsible()
                                    ->columnSpanFull(),
                            ])
                            ->defaultItems(0)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string =>
                                ($state['color'] ?? 'Color') .
                                (isset($state['sizes']) && count($state['sizes']) > 0 ? ' (' . count($state['sizes']) . ' sizes)' : '')
                            )
                            ->addActionLabel('Add Color Family')
                            ->visible(fn ($get) => $get('variant_type') === 'both'),
                    ])
                    ->columns(1)
                    ->columnSpanFull(),
            ])
            ->columns(1);
    }
}

<?php

namespace App\Filament\Resources\DigitalProducts;

use App\Filament\Resources\DigitalProducts\Pages\CreateDigitalProduct;
use App\Filament\Resources\DigitalProducts\Pages\EditDigitalProduct;
use App\Filament\Resources\DigitalProducts\Pages\ListDigitalProducts;
use App\Filament\Resources\DigitalProducts\Pages\ViewDigitalProduct;
use App\Filament\Resources\DigitalProducts\Schemas\DigitalProductForm;
use App\Filament\Resources\DigitalProducts\Schemas\DigitalProductInfolist;
use App\Filament\Resources\DigitalProducts\Tables\DigitalProductsTable;
use App\Models\DigitalProduct;
use App\NavigationGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class DigitalProductResource extends Resource
{
    protected static ?string $model = DigitalProduct::class;

    protected static ?string $modelLabel = 'Digital Products';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::DocumentArrowUp;

    protected static ?int $navigationSort = 4;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::products->value;

    public static function form(Schema $schema): Schema
    {
        return DigitalProductForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DigitalProductInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DigitalProductsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDigitalProducts::route('/'),
            'create' => CreateDigitalProduct::route('/create'),
            'view' => ViewDigitalProduct::route('/{record}'),
            'edit' => EditDigitalProduct::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return $user?->isAdmin() || $user?->isModerator() ?? false;
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();

        return $user?->isAdmin() || $user?->isModerator() ?? false;
    }

    public static function canUpdate(Model $record): bool
    {
        $user = auth()->user();

        return $user?->isAdmin() || $user?->isModerator() ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        $user = auth()->user();

        return $user?->isAdmin() ?? false;
    }
}

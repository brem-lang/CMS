<?php

namespace App\Filament\Resources\Couriers;

use App\Filament\Resources\Couriers\Pages\CreateCourier;
use App\Filament\Resources\Couriers\Pages\EditCourier;
use App\Filament\Resources\Couriers\Pages\ListCouriers;
use App\Filament\Resources\Couriers\Pages\ViewCourier;
use App\Filament\Resources\Couriers\Schemas\CourierForm;
use App\Filament\Resources\Couriers\Schemas\CourierInfolist;
use App\Filament\Resources\Couriers\Tables\CouriersTable;
use App\Models\Courier;
use App\NavigationGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class CourierResource extends Resource
{
    protected static ?string $model = Courier::class;

    protected static ?string $modelLabel = 'Courier';

    protected static ?string $pluralModelLabel = 'Couriers';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Truck;

    protected static ?int $navigationSort = 3;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::sales->value;

    public static function form(Schema $schema): Schema
    {
        return CourierForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CourierInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CouriersTable::configure($table);
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
            'index' => ListCouriers::route('/'),
            'create' => CreateCourier::route('/create'),
            'view' => ViewCourier::route('/{record}'),
            'edit' => EditCourier::route('/{record}/edit'),
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

        return $user?->isAdmin() ?? false;
    }

    public static function canUpdate(Model $record): bool
    {
        $user = auth()->user();

        return $user?->isAdmin() ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        $user = auth()->user();

        return $user?->isAdmin() ?? false;
    }
}

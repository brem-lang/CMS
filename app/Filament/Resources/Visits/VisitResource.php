<?php

namespace App\Filament\Resources\Visits;

use App\Filament\Resources\Visits\Pages\ListVisits;
use App\Filament\Resources\Visits\Tables\VisitsTable;
use App\Models\Visit;
use App\NavigationGroup;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class VisitResource extends Resource
{
    protected static ?string $model = Visit::class;

    protected static ?string $modelLabel = 'Visit';

    protected static ?string $pluralModelLabel = 'Visits';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Eye;

    protected static ?int $navigationSort = 11;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::analytics->value;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('ip_address')->disabled(),
            TextInput::make('url')->disabled(),
            TextInput::make('visited_at')->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return VisitsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVisits::route('/'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return $user?->isAdmin() || $user?->isModerator() ?? false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        $user = auth()->user();

        return $user?->isAdmin() || $user?->isModerator() ?? false;
    }
}

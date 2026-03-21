<?php

namespace App\Filament\Resources\MyContents;

use App\Filament\Resources\MyContents\Pages\CreateMyContent;
use App\Filament\Resources\MyContents\Pages\EditMyContent;
use App\Filament\Resources\MyContents\Pages\ListMyContents;
use App\Filament\Resources\MyContents\Schemas\MyContentForm;
use App\Filament\Resources\MyContents\Tables\MyContentsTable;
use App\Models\MyContent;
use App\NavigationGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class MyContentResource extends Resource
{
    protected static ?string $model = MyContent::class;

    protected static ?string $modelLabel = 'My Content';

    protected static ?string $pluralModelLabel = 'My Contents';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::VideoCamera;

    protected static ?int $navigationSort = 12;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::products->value;

    public static function form(Schema $schema): Schema
    {
        return MyContentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MyContentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMyContents::route('/'),
            'create' => CreateMyContent::route('/create'),
            'edit' => EditMyContent::route('/{record}/edit'),
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

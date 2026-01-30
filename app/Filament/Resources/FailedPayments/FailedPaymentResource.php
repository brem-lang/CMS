<?php

namespace App\Filament\Resources\FailedPayments;

use App\Filament\Resources\FailedPayments\Pages\CreateFailedPayment;
use App\Filament\Resources\FailedPayments\Pages\EditFailedPayment;
use App\Filament\Resources\FailedPayments\Pages\ListFailedPayments;
use App\Filament\Resources\FailedPayments\Pages\ViewFailedPayment;
use App\Filament\Resources\FailedPayments\Schemas\FailedPaymentForm;
use App\Filament\Resources\FailedPayments\Schemas\FailedPaymentInfolist;
use App\Filament\Resources\FailedPayments\Tables\FailedPaymentsTable;
use App\Models\FailedPayment;
use App\NavigationGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class FailedPaymentResource extends Resource
{
    protected static ?string $model = FailedPayment::class;

    protected static ?string $modelLabel = 'Failed Payment';

    protected static ?string $pluralModelLabel = 'Failed Payments';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::XCircle;

    protected static ?int $navigationSort = 2;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::sales->value;

    public static function form(Schema $schema): Schema
    {
        return FailedPaymentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FailedPaymentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FailedPaymentsTable::configure($table);
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
            'index' => ListFailedPayments::route('/'),
            // 'create' => CreateFailedPayment::route('/create'),
            'view' => ViewFailedPayment::route('/{record}'),
            // 'edit' => EditFailedPayment::route('/{record}/edit'),
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

    public static function canUpdate(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}

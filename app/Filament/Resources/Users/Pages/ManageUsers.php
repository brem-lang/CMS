<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('New User')
                ->icon('heroicon-o-user-plus'),
        ];
    }

    public function getBreadcrumbs(): array
    {
        return [
            'users' => 'User Managements',
            'List',
        ];
    }
}

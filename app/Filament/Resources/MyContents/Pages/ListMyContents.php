<?php

namespace App\Filament\Resources\MyContents\Pages;

use App\Filament\Resources\MyContents\MyContentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMyContents extends ListRecords
{
    protected static string $resource = MyContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon('heroicon-o-plus'),
        ];
    }
}

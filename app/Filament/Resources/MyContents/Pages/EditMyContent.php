<?php

namespace App\Filament\Resources\MyContents\Pages;

use App\Filament\Resources\MyContents\MyContentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMyContent extends EditRecord
{
    protected static string $resource = MyContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->hidden(fn () => auth()->user()?->isModerator() ?? false),
        ];
    }
}

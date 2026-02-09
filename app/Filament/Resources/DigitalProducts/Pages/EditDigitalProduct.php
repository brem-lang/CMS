<?php

namespace App\Filament\Resources\DigitalProducts\Pages;

use App\Filament\Resources\DigitalProducts\DigitalProductResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDigitalProduct extends EditRecord
{
    protected static string $resource = DigitalProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->hidden(fn () => auth()->user()?->isModerator() ?? false),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Set price to 0 if product is free
        if (isset($data['is_free']) && $data['is_free']) {
            $data['price'] = 0;
        }

        return $data;
    }
}

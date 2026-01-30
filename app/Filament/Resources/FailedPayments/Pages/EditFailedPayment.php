<?php

namespace App\Filament\Resources\FailedPayments\Pages;

use App\Filament\Resources\FailedPayments\FailedPaymentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFailedPayment extends EditRecord
{
    protected static string $resource = FailedPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->hidden(fn () => auth()->user()?->isModerator() ?? false),
        ];
    }
}

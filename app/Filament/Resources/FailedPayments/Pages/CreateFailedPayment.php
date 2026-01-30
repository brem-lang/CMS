<?php

namespace App\Filament\Resources\FailedPayments\Pages;

use App\Filament\Resources\FailedPayments\FailedPaymentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFailedPayment extends CreateRecord
{
    protected static string $resource = FailedPaymentResource::class;
}

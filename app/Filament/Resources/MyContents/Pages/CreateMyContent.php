<?php

namespace App\Filament\Resources\MyContents\Pages;

use App\Filament\Resources\MyContents\MyContentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMyContent extends CreateRecord
{
    protected static string $resource = MyContentResource::class;
}

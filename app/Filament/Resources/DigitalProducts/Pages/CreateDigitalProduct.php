<?php

namespace App\Filament\Resources\DigitalProducts\Pages;

use App\Filament\Resources\DigitalProducts\DigitalProductResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateDigitalProduct extends CreateRecord
{
    protected static string $resource = DigitalProductResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $data['added_by'] = auth()->user()->id;
        
        // Set price to 0 if product is free
        if (isset($data['is_free']) && $data['is_free']) {
            $data['price'] = 0;
        }

        return parent::handleRecordCreation($data);
    }
}

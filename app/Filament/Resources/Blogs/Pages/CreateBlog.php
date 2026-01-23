<?php

namespace App\Filament\Resources\Blogs\Pages;

use App\Filament\Resources\Blogs\BlogResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateBlog extends CreateRecord
{
    protected static string $resource = BlogResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $data['user_id'] = auth()->user()->id;

        return parent::handleRecordCreation($data);
    }
}

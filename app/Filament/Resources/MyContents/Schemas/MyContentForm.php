<?php

namespace App\Filament\Resources\MyContents\Schemas;

use App\Models\MyContent;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MyContentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->maxLength(255),
                        FileUpload::make('video_path')
                            ->label('Video')
                            ->disk('public')
                            ->directory('my-contents/videos')
                            ->acceptedFileTypes([
                                'video/mp4',
                                'video/webm',
                                'video/quicktime',
                                'video/x-msvideo',
                            ])
                            ->maxSize(102400)
                            ->visibility('public')
                            ->required(),
                        Toggle::make('highlights')
                            ->label('Highlights')
                            ->helperText('ON = shown on home page')
                            ->default(false)
                            ->rule(fn ($record) => function (string $attribute, mixed $value, Closure $fail) use ($record): void {
                                if (! $value) {
                                    return;
                                }

                                $query = MyContent::query()->where('highlights', true);

                                if ($record?->exists) {
                                    $query->whereKeyNot($record->getKey());
                                }

                                if ($query->count() >= 4) {
                                    $fail('Only 4 highlighted videos are allowed.');
                                }
                            }),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}

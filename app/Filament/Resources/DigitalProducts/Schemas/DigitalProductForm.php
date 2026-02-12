<?php

namespace App\Filament\Resources\DigitalProducts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class DigitalProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('title')
                            ->label('Product Title')
                            ->columnSpanFull()
                            ->required()
                            ->maxLength(255),
                        Textarea::make('description')
                            ->label('Description')
                            ->columnSpanFull()
                            ->required()
                            ->rows(4),
                        Toggle::make('is_free')
                            ->label('Free Product')
                            ->helperText('ON = Free | OFF = Paid')
                            ->onIcon(Heroicon::Check)
                            ->offIcon(Heroicon::XMark)
                            ->inline(false)
                            ->default(false)
                            ->live()
                            ->required(),
                        TextInput::make('price')
                            ->label('Price')
                            ->required(fn ($get) => !$get('is_free'))
                            ->numeric()
                            ->prefix('₱')
                            ->minValue(0)
                            ->default(0)
                            ->visible(fn ($get) => !$get('is_free'))
                            ->helperText('Price is required for paid products'),
                        Select::make('file_type')
                            ->label('File Type')
                            ->options([
                                'pdf' => 'PDF',
                                'audio' => 'Audio',
                            ])
                            ->required()
                            ->default('pdf')
                            ->live()
                            ->helperText('Select the type of digital file'),
                        FileUpload::make('thumbnail')
                            ->label('Thumbnail Image')
                            ->disk('public')
                            ->directory('digital-products/thumbnails')
                            ->image()
                            ->maxSize(2048)
                            ->imageEditor()
                            ->visibility('public')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                            ->helperText('Upload a thumbnail image for this digital product')
                            ->columnSpanFull(),
                        FileUpload::make('file_path')
                            ->label('Digital File')
                            ->disk('public')
                            ->directory('digital-products/files')
                            ->maxSize(10240)
                            ->visibility('public')
                            ->acceptedFileTypes([
                                'application/pdf',
                                'audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg',
                                'audio/m4a', 'audio/x-m4a', 'audio/mp4',
                            ])
                            ->helperText(fn ($get) =>
                                $get('file_type') === 'pdf'
                                    ? 'Upload a PDF file (max 10MB)'
                                    : 'Upload an audio file (max 10MB)'
                            )
                            ->required()
                            ->rule(function ($get) {
                                $fileType = $get('file_type');
                                $allowedMimes = $fileType === 'pdf'
                                    ? ['application/pdf']
                                    : ['audio/mpeg', 'audio/mp3', 'audio/wav', 'audio/ogg', 'audio/m4a', 'audio/x-m4a', 'audio/mp4'];
                                $allowedExtensions = $fileType === 'pdf' ? ['pdf'] : ['mp3', 'm4a', 'wav', 'ogg'];
                                return function (string $attribute, $value, \Closure $fail) use ($allowedMimes, $allowedExtensions) {
                                    if (! $value) {
                                        return;
                                    }
                                    $path = is_array($value) ? ($value[0] ?? null) : $value;
                                    if (! $path) {
                                        return;
                                    }
                                    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                    if (! in_array($ext, $allowedExtensions, true)) {
                                        $fail('The uploaded file type does not match the selected file type (PDF or Audio).');
                                        return;
                                    }
                                    $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($path);
                                    if (file_exists($fullPath)) {
                                        $mime = \Illuminate\Support\Facades\File::mimeType($fullPath);
                                        if (! in_array($mime, $allowedMimes, true)) {
                                            $fail('The uploaded file type does not match the selected file type (PDF or Audio).');
                                        }
                                    }
                                };
                            })
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('When active, this product will be visible to users')
                            ->onIcon(Heroicon::Check)
                            ->offIcon(Heroicon::XMark)
                            ->inline(false)
                            ->default(true)
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ])
            ->columns(1);
    }
}

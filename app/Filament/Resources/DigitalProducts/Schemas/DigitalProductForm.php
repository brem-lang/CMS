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
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

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
                            ->afterStateUpdated(fn ($state, $set) => $set('file_path', null))
                            ->helperText('Select the type first (PDF or Audio), then upload a file of that type.'),
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
                            ->helperText(fn ($get) =>
                                $get('file_type') === 'pdf'
                                    ? 'Upload a PDF file (max 10MB)'
                                    : 'Upload an audio file (max 10MB)'
                            )
                            ->required()
                            ->rule(function ($get) {
                                return function (string $attribute, $value, \Closure $fail) use ($get) {
                                    if (! $value) {
                                        return;
                                    }
                                    $path = is_array($value) ? ($value[0] ?? null) : $value;
                                    if (! $path) {
                                        return;
                                    }
                                    $ext = null;
                                    if ($path instanceof TemporaryUploadedFile) {
                                        $ext = strtolower($path->getClientOriginalExtension());
                                    } elseif (is_array($path)) {
                                        $path = $path['name'] ?? $path['path'] ?? $path[0] ?? '';
                                        $ext = strtolower(pathinfo((string) $path, PATHINFO_EXTENSION));
                                    } else {
                                        $path = (string) $path;
                                        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                    }
                                    $fileType = $get('file_type');
                                    $audioExtensions = ['mp3', 'm4a', 'wav', 'ogg'];
                                    $isAudioExt = $ext && in_array($ext, $audioExtensions, true);
                                    $isPdfExt = $ext === 'pdf';
                                    if ($isAudioExt) {
                                        if ($fileType === 'pdf') {
                                            $fail('You selected PDF but uploaded an audio file. Please set File Type to "Audio".');
                                        }
                                        return;
                                    }
                                    if ($isPdfExt) {
                                        if ($fileType !== 'pdf') {
                                            $fail('You selected Audio but uploaded a PDF. Please set File Type to "PDF".');
                                            return;
                                        }
                                        if (is_string($path) && ! ($path instanceof TemporaryUploadedFile)) {
                                            $fullPath = \Illuminate\Support\Facades\Storage::disk('public')->path($path);
                                            if (file_exists($fullPath)) {
                                                $mime = \Illuminate\Support\Facades\File::mimeType($fullPath);
                                                if ($mime !== 'application/pdf') {
                                                    $fail('The uploaded file is not a valid PDF.');
                                                }
                                            }
                                        }
                                        return;
                                    }
                                    $fail('The uploaded file type does not match the selected file type (PDF or Audio). Accepted: PDF or .mp3, .m4a, .wav, .ogg');
                                };
                            })
                            ->columnSpanFull(),
                        Toggle::make('for_subscribers')
                            ->label('For Subscribers')
                            ->helperText('When ON, this product can be sent as a gift to subscribers. Only one product can be selected at a time.')
                            ->onIcon(Heroicon::Check)
                            ->offIcon(Heroicon::XMark)
                            ->inline(false)
                            ->default(false)
                            ->required(),
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

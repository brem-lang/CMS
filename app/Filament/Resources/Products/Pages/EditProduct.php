<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\ProductVariant;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make()
                ->hidden(fn () => auth()->user()?->isModerator() ?? false),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Group variants by color for the form
        $product = $this->record;
        $variants = $product->variants()->get();
        
        $colorVariants = [];
        $groupedByColor = $variants->groupBy('color');

        foreach ($groupedByColor as $color => $colorGroup) {
            $firstVariant = $colorGroup->first();
            $sizes = [];

            foreach ($colorGroup as $variant) {
                $sizes[] = [
                    'size' => $variant->size,
                    'quantity' => $variant->quantity,
                ];
            }

            $colorVariants[] = [
                'color' => $color,
                'color_image' => $firstVariant->color_image,
                'sizes' => $sizes,
            ];
        }

        $data['color_variants'] = $colorVariants;

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Extract color variants before updating
        $colorVariants = $data['color_variants'] ?? [];
        unset($data['color_variants']);

        // Update the product
        $record = parent::handleRecordUpdate($record, $data);

        // Delete existing variants
        $record->variants()->delete();

        // Create new variants from color variants structure
        if (!empty($colorVariants)) {
            foreach ($colorVariants as $colorVariant) {
                $color = $colorVariant['color'] ?? null;
                $colorImage = $colorVariant['color_image'] ?? null;
                $sizes = $colorVariant['sizes'] ?? [];

                foreach ($sizes as $sizeData) {
                    ProductVariant::create([
                        'product_id' => $record->id,
                        'color' => $color,
                        'color_image' => is_array($colorImage) ? ($colorImage[0] ?? null) : $colorImage,
                        'size' => $sizeData['size'] ?? null,
                        'quantity' => $sizeData['quantity'] ?? 0,
                    ]);
                }
            }
        }

        return $record;
    }
}

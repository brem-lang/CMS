<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\ProductVariant;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $data['added_by'] = auth()->user()->id;

        // Extract color variants before creating the product
        $colorVariants = $data['color_variants'] ?? [];
        unset($data['color_variants']);

        // Create the product
        $product = parent::handleRecordCreation($data);

        // Create variants from color variants structure
        if (!empty($colorVariants)) {
            foreach ($colorVariants as $colorVariant) {
                $color = $colorVariant['color'] ?? null;
                $colorImage = $colorVariant['color_image'] ?? null;
                $sizes = $colorVariant['sizes'] ?? [];

                foreach ($sizes as $sizeData) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color' => $color,
                        'color_image' => is_array($colorImage) ? ($colorImage[0] ?? null) : $colorImage,
                        'size' => $sizeData['size'] ?? null,
                        'quantity' => $sizeData['quantity'] ?? 0,
                    ]);
                }
            }
        }

        return $product;
    }
}

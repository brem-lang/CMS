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

        // Extract color variants and has_variants before creating the product
        $colorVariants = $data['color_variants'] ?? [];
        $hasVariants = $data['has_variants'] ?? false;
        unset($data['color_variants'], $data['has_variants']);

        // Calculate total stock quantity from variants if variants are enabled
        if ($hasVariants && !empty($colorVariants)) {
            $totalStock = 0;
            foreach ($colorVariants as $colorVariant) {
                $sizes = $colorVariant['sizes'] ?? [];
                foreach ($sizes as $sizeData) {
                    $totalStock += (int)($sizeData['quantity'] ?? 0);
                }
            }
            $data['stock_quantity'] = $totalStock;
        }

        // Create the product
        $product = parent::handleRecordCreation($data);

        // Create variants from color variants structure only if has_variants is true
        if ($hasVariants && !empty($colorVariants)) {
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

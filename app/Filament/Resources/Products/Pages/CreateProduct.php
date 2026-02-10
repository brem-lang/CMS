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

        $hasVariants = $data['has_variants'] ?? false;
        $variantType = $data['variant_type'] ?? 'both';
        $sizeVariants = $data['size_variants'] ?? [];
        $colorOnlyVariants = $data['color_only_variants'] ?? [];
        $colorVariants = $data['color_variants'] ?? [];

        unset($data['color_variants'], $data['has_variants'], $data['variant_type'], $data['size_variants'], $data['color_only_variants']);

        if ($hasVariants) {
            $data['variant_type'] = $variantType;
            $data['stock_quantity'] = self::calculateVariantStock($variantType, $sizeVariants, $colorOnlyVariants, $colorVariants);
        }

        $product = parent::handleRecordCreation($data);

        if ($hasVariants) {
            $this->createVariantsFromForm($product->id, $variantType, $sizeVariants, $colorOnlyVariants, $colorVariants);
        }

        return $product;
    }

    public static function calculateVariantStock(string $variantType, array $sizeVariants, array $colorOnlyVariants, array $colorVariants): int
    {
        $total = 0;
        if ($variantType === 'size') {
            foreach ($sizeVariants as $row) {
                $total += (int)($row['quantity'] ?? 0);
            }
        } elseif ($variantType === 'color') {
            foreach ($colorOnlyVariants as $row) {
                $total += (int)($row['quantity'] ?? 0);
            }
        } else {
            foreach ($colorVariants as $colorVariant) {
                foreach ($colorVariant['sizes'] ?? [] as $sizeData) {
                    $total += (int)($sizeData['quantity'] ?? 0);
                }
            }
        }
        return $total;
    }

    private function normalizeImages(mixed $images): array
    {
        if (is_array($images)) {
            return array_values(array_filter($images));
        }
        return $images ? [$images] : [];
    }

    private function createVariantsFromForm(int $productId, string $variantType, array $sizeVariants, array $colorOnlyVariants, array $colorVariants): void
    {
        if ($variantType === 'size') {
            foreach ($sizeVariants as $row) {
                ProductVariant::create([
                    'product_id' => $productId,
                    'size' => $row['size'] ?? null,
                    'color' => null,
                    'quantity' => (int)($row['quantity'] ?? 0),
                    'images' => $this->normalizeImages($row['images'] ?? null),
                ]);
            }
            return;
        }

        if ($variantType === 'color') {
            foreach ($colorOnlyVariants as $row) {
                ProductVariant::create([
                    'product_id' => $productId,
                    'size' => null,
                    'color' => $row['color'] ?? null,
                    'quantity' => (int)($row['quantity'] ?? 0),
                    'images' => $this->normalizeImages($row['images'] ?? null),
                ]);
            }
            return;
        }

        foreach ($colorVariants as $colorVariant) {
            $color = $colorVariant['color'] ?? null;
            foreach ($colorVariant['sizes'] ?? [] as $sizeData) {
                ProductVariant::create([
                    'product_id' => $productId,
                    'size' => $sizeData['size'] ?? null,
                    'color' => $color,
                    'quantity' => (int)($sizeData['quantity'] ?? 0),
                    'images' => $this->normalizeImages($sizeData['images'] ?? null),
                ]);
            }
        }
    }
}

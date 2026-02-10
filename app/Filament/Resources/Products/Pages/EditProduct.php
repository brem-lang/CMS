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
        $product = $this->record;
        $variants = $product->variants()->get();

        $data['has_variants'] = $variants->isNotEmpty();
        $data['variant_type'] = $product->variant_type ?? 'both';

        if ($variants->isEmpty()) {
            $data['size_variants'] = [];
            $data['color_only_variants'] = [];
            $data['color_variants'] = [];
            return $data;
        }

        $variantType = $data['variant_type'];

        if ($variantType === 'size') {
            $data['size_variants'] = $variants->map(fn ($v) => [
                'size' => $v->size,
                'quantity' => $v->quantity,
                'images' => $v->images ?? [],
            ])->values()->all();
            $data['color_only_variants'] = [];
            $data['color_variants'] = [];
            return $data;
        }

        if ($variantType === 'color') {
            $data['color_only_variants'] = $variants->map(fn ($v) => [
                'color' => $v->color,
                'quantity' => $v->quantity,
                'images' => $v->images ?? [],
            ])->values()->all();
            $data['size_variants'] = [];
            $data['color_variants'] = [];
            return $data;
        }

        $colorVariants = [];
        foreach ($variants->groupBy('color') as $color => $colorGroup) {
            $sizes = $colorGroup->map(fn ($v) => [
                'size' => $v->size,
                'quantity' => $v->quantity,
                'images' => $v->images ?? [],
            ])->values()->all();
            $colorVariants[] = ['color' => $color, 'sizes' => $sizes];
        }
        $data['color_variants'] = $colorVariants;
        $data['size_variants'] = [];
        $data['color_only_variants'] = [];

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $hasVariants = $data['has_variants'] ?? false;
        $variantType = $data['variant_type'] ?? 'both';
        $sizeVariants = $data['size_variants'] ?? [];
        $colorOnlyVariants = $data['color_only_variants'] ?? [];
        $colorVariants = $data['color_variants'] ?? [];

        unset($data['color_variants'], $data['has_variants'], $data['variant_type'], $data['size_variants'], $data['color_only_variants']);

        if ($hasVariants) {
            $data['variant_type'] = $variantType;
            $data['stock_quantity'] = CreateProduct::calculateVariantStock($variantType, $sizeVariants, $colorOnlyVariants, $colorVariants);
        }

        $record = parent::handleRecordUpdate($record, $data);

        $record->variants()->delete();

        if ($hasVariants) {
            $this->createVariantsFromForm($record->id, $variantType, $sizeVariants, $colorOnlyVariants, $colorVariants);
        }

        return $record;
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

<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\CartService;
use App\View\Components\Layout\App;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(App::class)]
class ViewProduct extends Component
{
    public $product;

    public $selectedSize = null;

    public $selectedColor = null;

    public $quantity = 1;

    public function mount($id)
    {
        $this->loadProduct($id);
    }

    protected function loadProduct($id)
    {
        $this->product = Product::with('variants')
            ->where('id', $id)
            ->where('status', true)
            ->firstOrFail();

        // Ensure arrays are properly cast
        if ($this->product->size_options && is_string($this->product->size_options)) {
            $this->product->size_options = json_decode($this->product->size_options, true) ?? [];
        }
        if ($this->product->color_options && is_string($this->product->color_options)) {
            $this->product->color_options = json_decode($this->product->color_options, true) ?? [];
        }
    }

    public function incrementQuantity()
    {
        $maxQuantity = $this->getAvailableQuantity();
        if ($this->quantity < $maxQuantity) {
            $this->quantity++;
        }
    }

    public function decrementQuantity()
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function selectSize($sizeName)
    {
        $this->selectedSize = $sizeName;
        $this->updateQuantityBasedOnVariant();
    }

    public function selectColor($colorName)
    {
        $this->selectedColor = $colorName;
        // Reset size selection when color changes
        $this->selectedSize = null;
        $this->updateQuantityBasedOnVariant();
    }

    public function selectVariant($color, $size)
    {
        $this->selectedColor = $color ?: null;
        $this->selectedSize = $size ?: null;
        $this->updateQuantityBasedOnVariant();
    }

    protected function updateQuantityBasedOnVariant()
    {
        $availableQuantity = $this->getAvailableQuantity();
        if ($this->quantity > $availableQuantity) {
            $this->quantity = max(1, $availableQuantity);
        }
    }

    public function getAvailableQuantity()
    {
        // If product has variants, check variant stock
        if ($this->product->variants && $this->product->variants->count() > 0) {
            $variantType = $this->product->variant_type ?? 'both';

            if ($variantType === 'size' && empty($this->selectedSize)) {
                return 0;
            }
            if ($variantType === 'color' && empty($this->selectedColor)) {
                return 0;
            }
            if ($variantType === 'both' && (empty($this->selectedSize) || empty($this->selectedColor))) {
                return 0;
            }

            $variant = $this->product->variants->first(function ($variant) {
                return $variant->size === $this->selectedSize &&
                    $variant->color === $this->selectedColor;
            });

            if ($variant) {
                return $variant->quantity ?? 0;
            }

            return 0;
        }

        // Fallback to product stock_quantity
        return $this->product->stock_quantity ?? 0;
    }

    public function getSelectedVariant()
    {
        if (! $this->product->variants || $this->product->variants->count() === 0) {
            return null;
        }

        return $this->product->variants->first(function ($variant) {
            $sizeMatch = ($variant->size === $this->selectedSize) ||
                (empty($variant->size) && empty($this->selectedSize));
            $colorMatch = ($variant->color === $this->selectedColor) ||
                (empty($variant->color) && empty($this->selectedColor));

            return $sizeMatch && $colorMatch;
        });
    }

    /**
     * Get the image URL for the selected variant or product image (supports size-only, color-only, both)
     */
    public function getDisplayImageUrl()
    {
        $hasVariants = $this->product->variants && $this->product->variants->count() > 0;

        if ($hasVariants) {
            $selectedVariant = $this->getSelectedVariant();
            if ($selectedVariant && ! empty($selectedVariant->images) && $selectedVariant->color_image_url) {
                return $selectedVariant->color_image_url;
            }
        }

        if (! $hasVariants && $this->product->product_image_url) {
            return $this->product->product_image_url;
        }

        return $this->product->image_url;
    }

    /**
     * Get all images for display (variants or product images)
     */
    public function getAllDisplayImages()
    {
        $hasVariants = $this->product->variants && $this->product->variants->count() > 0;

        if ($hasVariants) {
            $selectedVariant = $this->getSelectedVariant();
            if ($selectedVariant && ! empty($selectedVariant->images)) {
                return $selectedVariant->images_urls;
            }
            $variantType = $this->product->variant_type ?? 'both';
            $groupKey = ($variantType === 'size') ? 'size' : 'color';
            return $this->product->variants->filter(fn ($v) => ! empty($v->images))
                ->groupBy($groupKey)
                ->map(function ($variants) {
                    $first = $variants->first();
                    return $first && $first->color_image_url ? [$first->color_image_url] : [];
                })
                ->filter()
                ->values()
                ->flatten()
                ->unique()
                ->values()
                ->toArray();
        }

        $images = [];
        if ($this->product->product_image_url) {
            $images[] = $this->product->product_image_url;
        }
        $additionalImages = $this->product->additional_images_urls ?? [];
        return array_values(array_filter(array_merge($images, $additionalImages)));
    }

    /**
     * Get total quantity for a specific size (sum of all colors for that size)
     */
    public function getSizeQuantity($sizeName)
    {
        if (! $this->product->variants || $this->product->variants->count() === 0) {
            return null;
        }

        return $this->product->variants
            ->where('size', $sizeName)
            ->sum('quantity');
    }

    /**
     * Get total quantity for a specific color (sum of all sizes for that color)
     */
    public function getColorQuantity($colorName)
    {
        if (! $this->product->variants || $this->product->variants->count() === 0) {
            return null;
        }

        return $this->product->variants
            ->where('color', $colorName)
            ->sum('quantity');
    }

    /**
     * Get quantity for a specific size/color combination (supports size-only, color-only, or both)
     */
    public function getVariantQuantity($sizeName, $colorName)
    {
        if (! $this->product->variants || $this->product->variants->count() === 0) {
            return null;
        }

        $variantType = $this->product->variant_type ?? 'both';
        if ($variantType === 'size' && (empty($sizeName) && $sizeName !== '0')) {
            return null;
        }
        if ($variantType === 'color' && (empty($colorName) && $colorName !== '0')) {
            return null;
        }
        if ($variantType === 'both' && (empty($sizeName) || empty($colorName))) {
            return null;
        }

        $variant = $this->product->variants->first(function ($variant) use ($sizeName, $colorName) {
            $sizeMatch = ($variant->size === $sizeName) || ($sizeName === null && empty($variant->size));
            $colorMatch = ($variant->color === $colorName) || ($colorName === null && empty($variant->color));
            return $sizeMatch && $colorMatch;
        });

        return $variant ? $variant->quantity : null;
    }

    /**
     * Check if a size-color combination is available
     */
    public function isVariantAvailable($sizeName, $colorName)
    {
        return $this->getVariantQuantity($sizeName, $colorName) > 0;
    }

    public function addToCart()
    {
        $availableQuantity = $this->getAvailableQuantity();

        // If product has variants, require variant selection based on variant type
        if ($this->product->variants && $this->product->variants->count() > 0) {
            $variantType = $this->product->variant_type ?? 'both';
            $needsSize = ($variantType === 'size' || $variantType === 'both') && empty($this->selectedSize);
            $needsColor = ($variantType === 'color' || $variantType === 'both') && empty($this->selectedColor);
            if ($needsSize || $needsColor) {
                $msg = $variantType === 'size' ? 'Please select a size.'
                    : ($variantType === 'color' ? 'Please select a color.'
                        : 'Please select both a color and a size.');
                $this->dispatch('cartUpdated', message: $msg, type: 'error');
                return;
            }

            $variant = $this->getSelectedVariant();
            if (! $variant) {
                $this->dispatch('cartUpdated', message: 'This variant is not available.', type: 'error');
                return;
            }
        }

        // Validate required options if enabled (for products without variants)
        if ($this->product->has_size_options && ! empty($this->product->size_options)) {
            if (empty($this->selectedSize)) {
                $this->dispatch('cartUpdated', message: 'Please select a size option.', type: 'error');

                return;
            }
        }

        if ($this->product->has_color_options && ! empty($this->product->color_options)) {
            if (empty($this->selectedColor)) {
                $this->dispatch('cartUpdated', message: 'Please select a color option.', type: 'error');

                return;
            }
        }

        // Check if product/variant is out of stock
        if ($availableQuantity <= 0) {
            $this->dispatch('cartUpdated', message: 'This product variant is currently out of stock.', type: 'error');

            return;
        }

        // Check if requested quantity exceeds available stock
        if ($this->quantity > $availableQuantity) {
            $this->dispatch('cartUpdated', message: 'Requested quantity exceeds available stock.', type: 'error');

            return;
        }

        // Add to cart with product ID, quantity, and variant selections (size and color)
        // The cart service will create separate cart items for different size/color combinations
        app(CartService::class)->addToCart(
            $this->product->id,
            $this->quantity,
            $this->selectedSize, // Size variant selection
            $this->selectedColor // Color variant selection
        );
        $this->dispatch('cartUpdated', message: 'Product added to cart successfully!');
        $this->quantity = 1;
        $this->selectedSize = null;
        $this->selectedColor = null;
    }

    public function buyNow()
    {
        $availableQuantity = $this->getAvailableQuantity();

        // If product has variants, require variant selection based on variant type
        if ($this->product->variants && $this->product->variants->count() > 0) {
            $variantType = $this->product->variant_type ?? 'both';
            $needsSize = ($variantType === 'size' || $variantType === 'both') && empty($this->selectedSize);
            $needsColor = ($variantType === 'color' || $variantType === 'both') && empty($this->selectedColor);
            if ($needsSize || $needsColor) {
                $msg = $variantType === 'size' ? 'Please select a size.'
                    : ($variantType === 'color' ? 'Please select a color.'
                        : 'Please select both a color and a size.');
                $this->dispatch('cartUpdated', message: $msg, type: 'error');
                return;
            }

            $variant = $this->getSelectedVariant();
            if (! $variant) {
                $this->dispatch('cartUpdated', message: 'This variant is not available.', type: 'error');
                return;
            }
        }

        // Validate required options if enabled (for products without variants)
        if ($this->product->has_size_options && ! empty($this->product->size_options)) {
            if (empty($this->selectedSize)) {
                $this->dispatch('cartUpdated', message: 'Please select a size option.', type: 'error');

                return;
            }
        }

        if ($this->product->has_color_options && ! empty($this->product->color_options)) {
            if (empty($this->selectedColor)) {
                $this->dispatch('cartUpdated', message: 'Please select a color option.', type: 'error');

                return;
            }
        }

        // Check if product/variant is out of stock
        if ($availableQuantity <= 0) {
            $this->dispatch('cartUpdated', message: 'This product variant is currently out of stock.', type: 'error');

            return;
        }

        // Check if requested quantity exceeds available stock
        if ($this->quantity > $availableQuantity) {
            $this->dispatch('cartUpdated', message: 'Requested quantity exceeds available stock.', type: 'error');

            return;
        }

        // Add to cart with product ID, quantity, and variant selections (size and color)
        app(CartService::class)->addToCart(
            $this->product->id,
            $this->quantity,
            $this->selectedSize, // Size variant selection
            $this->selectedColor // Color variant selection
        );

        // Redirect to checkout after successful addition
        return redirect()->route('checkout');
    }

    public function render()
    {
        return view('livewire.view-product');
    }
}

<div>
    <section class="shop-details">
        <div class="product__details__pic">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__breadcrumb">
                            <a href="{{ route('home') }}">Home</a>
                            <a href="{{ route('shop') }}">Shop</a>
                            <span>Product Details</span>
                        </div>
                    </div>
                </div>
                <div class="row" wire:ignore>
                    <div class="col-lg-3 col-md-3">
                        <ul class="nav nav-tabs" role="tablist">
                            @php
                                $hasVariants = $product->variants && $product->variants->count() > 0;
                                $displayImageUrl = $this->getDisplayImageUrl();

                                // Get all images for display
                                $allImages = $this->getAllDisplayImages();

                                // Ensure the displayed image is first in the list
                                $displayImages = [];
                                if ($displayImageUrl && !empty($allImages)) {
                                    // Start with the displayed image
                                    $displayImages[] = $displayImageUrl;
                                    // Add all other images that are different
                                    foreach ($allImages as $imageUrl) {
                                        if ($imageUrl !== $displayImageUrl) {
                                            $displayImages[] = $imageUrl;
                                        }
                                    }
                                } else {
                                    $displayImages = $allImages;
                                }

                                // Remove duplicates while preserving order
                                $displayImages = array_values(array_unique($displayImages));
                            @endphp
                            @foreach ($displayImages as $index => $image)
                                <li class="nav-item">
                                    <a class="nav-link {{ $index === 0 ? 'active' : '' }}" data-toggle="tab"
                                        href="#tabs-{{ $index + 1 }}" role="tab">
                                        <div class="product__thumb__pic set-bg" data-setbg="{{ $image }}">
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-lg-6 col-md-9">
                        <div class="tab-content">
                            @php
                                $hasVariants = $product->variants && $product->variants->count() > 0;
                                $displayImageUrl = $this->getDisplayImageUrl();

                                // Get all images for display (same logic as thumbnails)
                                $allImages = $this->getAllDisplayImages();

                                // Ensure the displayed image is first in the list
                                $displayImages = [];
                                if ($displayImageUrl && !empty($allImages)) {
                                    // Start with the displayed image
                                    $displayImages[] = $displayImageUrl;
                                    // Add all other images that are different
                                    foreach ($allImages as $imageUrl) {
                                        if ($imageUrl !== $displayImageUrl) {
                                            $displayImages[] = $imageUrl;
                                        }
                                    }
                                } else {
                                    $displayImages = $allImages;
                                }

                                // Remove duplicates while preserving order
                                $displayImages = array_values(array_unique($displayImages));
                            @endphp
                            @foreach ($displayImages as $index => $image)
                                <div class="tab-pane {{ $index === 0 ? 'active' : '' }}" id="tabs-{{ $index + 1 }}"
                                    role="tabpanel">
                                    <div class="product__details__pic__item" style="position: relative;">
                                        @php
                                            $isOutOfStock = false;
                                            if ($hasVariants) {
                                                $availableQty = $this->getAvailableQuantity();
                                                $isOutOfStock = $availableQty <= 0 && ($selectedSize || $selectedColor);
                                            } else {
                                                $isOutOfStock = ($product->stock_quantity ?? 0) == 0;
                                            }
                                        @endphp
                                        <img src="{{ $image }}" alt="{{ $product->name }}"
                                            style="width: 100%; height: auto; transition: opacity 0.3s ease;">
                                        @if ($isOutOfStock && $index === 0)
                                            <div
                                                style="position: absolute; top: 20px; right: 20px; background-color: rgba(220, 53, 69, 0.95); color: white; padding: 12px 20px; border-radius: 5px; font-weight: bold; font-size: 14px; text-transform: uppercase; z-index: 10; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                                                Out of Stock
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="product__details__content">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-8">
                        <div class="product__details__text">
                            <h4>{{ $product->name }}</h4>
                            {{-- <div class="rating">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star-o"></i>
                                <span> - 5 Reviews</span>
                            </div> --}}
                            <h3>₱{{ number_format($product->price, 2) }}</h3>
                            <p>{!! nl2br(e($product->description)) !!}</p>

                            @php
                                $hasVariants = $product->variants && $product->variants->count() > 0;

                                // Get unique sizes and colors from variants if they exist
                                $variantSizes = collect();
                                $variantColors = collect();

                                if ($hasVariants) {
                                    $variantSizes = $product->variants
                                        ->pluck('size')
                                        ->filter(function ($size) {
                                            return !empty($size);
                                        })
                                        ->unique()
                                        ->values();

                                    $variantColors = $product->variants
                                        ->pluck('color')
                                        ->filter(function ($color) {
                                            return !empty($color);
                                        })
                                        ->unique()
                                        ->values();
                                }

                                // Get sizes from product options
                                $productSizeOptions = [];
                                if ($product->size_options) {
                                    if (is_string($product->size_options)) {
                                        $decoded = json_decode($product->size_options, true);
                                        $productSizeOptions = is_array($decoded) ? $decoded : [];
                                    } elseif (is_array($product->size_options)) {
                                        $productSizeOptions = $product->size_options;
                                    }
                                }

                                // Get colors from product options
                                $productColorOptions = [];
                                if ($product->color_options) {
                                    if (is_string($product->color_options)) {
                                        $decoded = json_decode($product->color_options, true);
                                        $productColorOptions = is_array($decoded) ? $decoded : [];
                                    } elseif (is_array($product->color_options)) {
                                        $productColorOptions = $product->color_options;
                                    }
                                }

                                // Extract size names from product options
                                $productSizes = array_map(function ($option) {
                                    return is_array($option) ? $option['name'] ?? '' : $option;
                                }, $productSizeOptions);
                                $productSizes = array_filter($productSizes);

                                // Extract color names from product options
                                $productColors = array_map(function ($option) {
                                    return is_array($option) ? $option['name'] ?? '' : $option;
                                }, $productColorOptions);
                                $productColors = array_filter($productColors);

                                // Use variant data if available, otherwise use product options
                                $displaySizes = [];
                                if ($hasVariants && $variantSizes->count() > 0) {
                                    $displaySizes = $variantSizes->toArray();
                                } elseif (!empty($productSizes)) {
                                    $displaySizes = array_values($productSizes);
                                }

                                $displayColors = [];
                                if ($hasVariants && $variantColors->count() > 0) {
                                    $displayColors = $variantColors->toArray();
                                } elseif (!empty($productColors)) {
                                    $displayColors = array_values($productColors);
                                }
                            @endphp

                            @php
                                // Group variants by color family if variants exist
                                $colorFamilies = collect();
                                if ($hasVariants) {
                                    $colorFamilies = $product->variants
                                        ->groupBy('color')
                                        ->map(function ($variants, $color) {
                                            $firstVariant = $variants->first();
                                            return [
                                                'name' => $color,
                                                'image' => $firstVariant->color_image_url ?? null,
                                                'variants' => $variants,
                                                'total_quantity' => $variants->sum('quantity'),
                                            ];
                                        })
                                        ->values();
                                }
                            @endphp

                            @if ($hasVariants && $colorFamilies->count() > 0)
                                <div class="product__details__option" style="margin: 30px 0;">
                                    {{-- Size Section --}}
                                    @if (!empty($displaySizes))
                                        <div class="product__details__option__size" style="margin-bottom: 25px;">
                                            <span>Size:</span>
                                            @foreach ($displaySizes as $index => $sizeName)
                                                @php
                                                    $sizeId = 'size-' . $product->id . '-' . $index;
                                                    $isSelected = $selectedSize === $sizeName;
                                                    $variantQty = $selectedColor
                                                        ? $this->getVariantQuantity($sizeName, $selectedColor)
                                                        : null;
                                                    $hasVariant = $variantQty !== null;
                                                    $isAvailable = !$selectedColor || ($hasVariant && $variantQty > 0);
                                                @endphp
                                                @if (!empty($sizeName))
                                                    <label for="{{ $sizeId }}"
                                                        class="{{ $isSelected ? 'active' : '' }}"
                                                        style="cursor: pointer; opacity: {{ $hasVariant && !$isAvailable ? '0.6' : '1' }};">
                                                        {{ strtoupper($sizeName) }}
                                                        <input type="radio" id="{{ $sizeId }}"
                                                            wire:click="selectSize('{{ $sizeName }}')"
                                                            wire:model="selectedSize" value="{{ $sizeName }}"
                                                            name="size-{{ $product->id }}"
                                                            style="position: absolute; visibility: hidden;">
                                                    </label>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- Color Palette Section --}}
                                    <div class="product__details__option__color" style="margin-bottom: 25px;">
                                        <span>Color:</span>
                                        @foreach ($colorFamilies as $index => $colorFamily)
                                            @php
                                                $isSelected = $selectedColor === $colorFamily['name'];
                                                $colorHex = \App\Models\Product::getColorHex($colorFamily['name']);
                                                $isAvailable = $colorFamily['total_quantity'] > 0;
                                            @endphp
                                            <label for="color-{{ $index }}"
                                                class="color-palette-swatch {{ $isSelected ? 'active' : '' }}"
                                                wire:click="selectColor('{{ $colorFamily['name'] }}')"
                                                style="
                                                    background: {{ $colorHex }};
                                                    cursor: {{ $isAvailable ? 'pointer' : 'not-allowed' }};
                                                    opacity: {{ $isAvailable ? '1' : '0.5' }};
                                                "
                                                title="{{ $colorFamily['name'] }}">
                                                <input type="radio" id="color-{{ $index }}"
                                                    wire:model="selectedColor" value="{{ $colorFamily['name'] }}"
                                                    name="color-{{ $product->id }}"
                                                    style="position: absolute; visibility: hidden;">
                                                @if ($isSelected)
                                                    <i class="fa fa-check"
                                                        style="
                                                        position: absolute;
                                                        color: {{ $colorHex === '#FFFFFF' || $colorHex === '#000000' ? '#e53637' : '#fff' }};
                                                        font-size: 12px;
                                                        font-weight: bold;
                                                        top: 50%;
                                                        left: 50%;
                                                        transform: translate(-50%, -50%);
                                                        z-index: 10;
                                                    "></i>
                                                @endif
                                            </label>
                                        @endforeach
                                    </div>

                                    {{-- Show selected variant quantity --}}
                                    @if ($selectedSize && $selectedColor)
                                        @php
                                            $selectedVariantQty = $this->getAvailableQuantity();
                                        @endphp
                                        @if ($selectedVariantQty > 0)
                                            <div
                                                style="margin-top: 15px; padding: 10px; background-color: #f0f8ff; border-left: 3px solid #007bff; border-radius: 4px;">
                                                <strong style="color: #007bff;">Available:</strong>
                                                <span
                                                    style="color: #28a745; font-weight: 600;">{{ $selectedVariantQty }}
                                                    units</span>
                                            </div>
                                        @else
                                            <div
                                                style="margin-top: 15px; padding: 10px; background-color: #fff3cd; border-left: 3px solid #ffc107; border-radius: 4px;">
                                                <strong style="color: #856404;">Out of Stock</strong>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            @elseif (($product->has_size_options && !empty($displaySizes)) || ($product->has_color_options && !empty($displayColors)))
                                {{-- Fallback to original design if no variants --}}
                                <div class="product__details__option">
                                    @if (!empty($displaySizes))
                                        <div class="product__details__option__size">
                                            <span>Size:</span>
                                            @foreach ($displaySizes as $index => $sizeName)
                                                @php
                                                    $sizeId = 'size-' . $product->id . '-' . $index;
                                                    $isSelected = $selectedSize === $sizeName;
                                                @endphp
                                                @if (!empty($sizeName))
                                                    <label for="{{ $sizeId }}"
                                                        class="{{ $isSelected ? 'active' : '' }}">
                                                        {{ strtoupper($sizeName) }}
                                                        <input type="radio" id="{{ $sizeId }}"
                                                            wire:model="selectedSize" value="{{ $sizeName }}"
                                                            name="size-{{ $product->id }}">
                                                    </label>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif

                                    @if (!empty($displayColors))
                                        <div class="product__details__option__color">
                                            <span>Color:</span>
                                            @foreach ($displayColors as $index => $colorName)
                                                @php
                                                    $isActive = $selectedColor === $colorName;
                                                    $colorHex = \App\Models\Product::getColorHex($colorName);
                                                @endphp
                                                @if (!empty($colorName))
                                                    <label for="sp-{{ $index }}"
                                                        class="color-swatch {{ $isActive ? 'active' : '' }}"
                                                        style="background: {{ $colorHex }}; position: relative;">
                                                        <input type="radio" id="sp-{{ $index }}"
                                                            wire:model="selectedColor" value="{{ $colorName }}"
                                                            name="color-{{ $product->id }}">
                                                        @if ($isActive)
                                                            <i class="fa fa-check"
                                                                style="position: absolute; color: {{ $colorHex === '#FFFFFF' || $colorHex === '#000000' ? '#e53637' : '#fff' }}; font-size: 14px; font-weight: bold; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;"></i>
                                                        @endif
                                                    </label>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif


                            <div class="product__details__cart__option">
                                @php
                                    $hasVariants = $product->variants && $product->variants->count() > 0;
                                    $availableQty = $hasVariants
                                        ? $this->getAvailableQuantity()
                                        : $product->stock_quantity ?? 0;
                                    $isOutOfStock = $availableQty <= 0;
                                    if ($hasVariants && !$selectedSize && !$selectedColor) {
                                        $isOutOfStock = false; // Don't disable if no variant selected yet
                                    }
                                @endphp
                                @if ($isOutOfStock)
                                    <div class="quantity" style="opacity: 0.5; pointer-events: none;">
                                        <div
                                            style="display: flex; align-items: center; border: 1px solid #e5e5e5; border-radius: 3px; width: fit-content;">
                                            <button type="button" disabled
                                                style="background: #f5f5f5; border: none; border-right: 1px solid #e5e5e5; padding: 10px 15px; cursor: not-allowed; font-size: 14px; font-weight: 700; color: #999999;">−</button>
                                            <input type="number" value="{{ $quantity }}" readonly
                                                style="border: none; background: transparent; text-align: center; width: 45px; font-weight: 600; color: #999999; padding: 10px 0; user-select: none; -moz-appearance: textfield;">
                                            <button type="button" disabled
                                                style="background: #f5f5f5; border: none; border-left: 1px solid #e5e5e5; padding: 10px 15px; cursor: not-allowed; font-size: 14px; font-weight: 700; color: #999999;">+</button>
                                        </div>
                                    </div>
                                    <a href="#" class="primary-btn"
                                        style="opacity: 0.5; cursor: not-allowed; pointer-events: none;"
                                        onclick="return false;">add to cart</a>
                                @else
                                    <div class="quantity">
                                        <div
                                            style="display: flex; align-items: center; border: 1px solid #e5e5e5; border-radius: 3px; width: fit-content;">
                                            <button type="button" wire:click="decrementQuantity"
                                                style="background: #f5f5f5; border: none; border-right: 1px solid #e5e5e5; padding: 10px 15px; cursor: pointer; font-size: 14px; font-weight: 700; color: #111111; transition: background 0.2s ease; user-select: none;"
                                                onmouseover="this.style.background='#ebebeb'"
                                                onmouseout="this.style.background='#f5f5f5'">−</button>
                                            <input type="number" value="{{ $quantity }}" readonly
                                                style="border: none; background: transparent; text-align: center; width: 45px; font-weight: 600; color: #111111; padding: 10px 0; user-select: none; -moz-appearance: textfield;">
                                            <button type="button" wire:click="incrementQuantity"
                                                style="background: #f5f5f5; border: none; border-left: 1px solid #e5e5e5; padding: 10px 15px; cursor: pointer; font-size: 14px; font-weight: 700; color: #111111; transition: background 0.2s ease; user-select: none;"
                                                onmouseover="this.style.background='#ebebeb'"
                                                onmouseout="this.style.background='#f5f5f5'">+</button>
                                        </div>
                                    </div>
                                    <a href="#" wire:click.prevent="addToCart" class="primary-btn">add to
                                        cart</a>
                                @endif
                            </div>
                            <div class="product__details__last__option">
                                <ul>
                                    <li><span>SKU:</span> {{ $product->id }}</li>
                                    <li><span>Stock:</span>
                                        @php
                                            $availableQty = $this->getAvailableQuantity();
                                            $hasVariants = $product->variants && $product->variants->count() > 0;
                                        @endphp
                                        @if ($hasVariants && ($selectedSize || $selectedColor))
                                            @if ($availableQty <= 0)
                                                <span style="color: #dc3545; font-weight: bold;">Out of Stock</span>
                                            @else
                                                <span style="color: #28a745; font-weight: bold;">{{ $availableQty }}
                                                    available</span>
                                                <span style="color: #666; font-size: 12px; margin-left: 5px;">
                                                    ({{ trim(($selectedColor ?? '') . ' ' . ($selectedSize ?? '')) }})
                                                </span>
                                            @endif
                                        @elseif ($hasVariants)
                                            <span style="color: #666;">Select variant to see stock</span>
                                        @else
                                            @if (($product->stock_quantity ?? 0) == 0)
                                                <span style="color: #dc3545; font-weight: bold;">Out of Stock</span>
                                            @else
                                                {{ $product->stock_quantity }} available
                                            @endif
                                        @endif
                                    </li>
                                    <li><span>Price:</span> ₱{{ number_format($product->price, 2) }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__tab">
                            <ul class="nav nav-tabs flex-nowrap overflow-auto justify-content-center" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#tabs-5"
                                        role="tab">Description</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-7" role="tab">Additional
                                        Information</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#tabs-6" role="tab">Customer
                                        Reviews</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tabs-5" role="tabpanel">
                                    <div class="product__details__tab__content">
                                        <div class="product__details__tab__content__item">
                                            <h5>Product Information</h5>
                                            <p>{{ $product->description }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-6" role="tabpanel">
                                    <div class="product__details__tab__content">
                                        <div class="product__details__tab__content__item">
                                            <h5>Customer Reviews</h5>
                                            <p>No reviews yet. Be the first to review this product!</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="tabs-7" role="tabpanel">
                                    <div class="product__details__tab__content">
                                        <div class="product__details__tab__content__item">
                                            <h5>Additional product information.</h5>
                                            <ul class="list-unstyled px-2">
                                                <li><span>Product ID:</span> {{ $product->id }}</li>
                                                <li><span>Name:</span> {{ $product->name }}</li>
                                                <li><span>Price:</span> ₱{{ number_format($product->price, 2) }}</li>
                                                <li><span>Stock:</span>
                                                    @php
                                                        $hasVariants =
                                                            $product->variants && $product->variants->count() > 0;
                                                    @endphp
                                                    @if ($hasVariants)
                                                        <span style="color: #666;">See variants table above</span>
                                                        @php
                                                            $totalVariantStock = $product->variants->sum('quantity');
                                                        @endphp
                                                        <span style="color: #666; margin-left: 10px;">(Total:
                                                            {{ $totalVariantStock }} units)</span>
                                                    @else
                                                        @if (($product->stock_quantity ?? 0) == 0)
                                                            <span style="color: #dc3545; font-weight: bold;">Out of
                                                                Stock</span>
                                                        @else
                                                            {{ $product->stock_quantity }}
                                                        @endif
                                                    @endif
                                                </li>
                                                @if ($hasVariants)
                                                    <li style="margin-top: 15px;margin-bottom: 25px;">
                                                        <span
                                                            style="font-weight: 600; display: block; margin-bottom: 10px;">Variants:</span>
                                                        <table
                                                            style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                                                            <thead>
                                                                <tr style="background-color: #f5f5f5;">
                                                                    <th
                                                                        style="padding: 8px; text-align: left; border: 1px solid #ddd;">
                                                                        Color</th>
                                                                    <th
                                                                        style="padding: 8px; text-align: left; border: 1px solid #ddd;">
                                                                        Size</th>
                                                                    <th
                                                                        style="padding: 8px; text-align: center; border: 1px solid #ddd;">
                                                                        Quantity</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($product->variants as $variant)
                                                                    <tr>
                                                                        <td
                                                                            style="padding: 8px; border: 1px solid #ddd;">
                                                                            {{ $variant->color ?: '—' }}</td>
                                                                        <td
                                                                            style="padding: 8px; border: 1px solid #ddd;">
                                                                            {{ $variant->size ?: '—' }}</td>
                                                                        <td
                                                                            style="padding: 8px; text-align: center; border: 1px solid #ddd; {{ ($variant->quantity ?? 0) <= 0 ? 'color: #dc3545;' : '' }}">
                                                                            {{ $variant->quantity ?? 0 }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .color-swatch {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            cursor: pointer;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            outline: 2px solid #e5e7eb;
            /* light gray for all */
            outline-offset: 2px;

            transition: outline-color 0.2s ease;
        }

        .color-swatch input {
            display: none;
        }

        /* SELECTED */
        .color-swatch:has(input:checked) {
            outline-color: #000;
            /* black ring */
        }

        /* Color Palette Swatches */
        .color-palette-swatch {
            height: 30px;
            width: 30px;
            border-radius: 50%;
            position: relative;
            margin-right: 10px;
            margin-bottom: 0;
            display: inline-block;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .color-palette-swatch:after {
            position: absolute;
            left: -3px;
            top: -3px;
            height: 36px;
            width: 36px;
            border: 1px solid #e5e5e5;
            content: "";
            border-radius: 50%;
            transition: border-color 0.2s ease;
        }

        .color-palette-swatch.active:after {
            border-color: #111;
            border-width: 2px;
        }

        .color-palette-swatch input {
            position: absolute;
            visibility: hidden;
        }

        .color-palette-swatch:hover:after {
            border-color: #999;
        }
    </style>
</div>

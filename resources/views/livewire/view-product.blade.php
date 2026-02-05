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
                <div class="row">
                    <div class="col-lg-3 col-md-3">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">
                                    <div class="product__thumb__pic set-bg" data-setbg="{{ $product->image_url }}">
                                    </div>
                                </a>
                            </li>
                            @foreach ($product->additional_images ?? [] as $image)
                                <li class="nav-item">
                                    @if (($product->stock_quantity ?? 0) == 0)
                                        <span class="nav-link"
                                            style="cursor: not-allowed; opacity: 0.6; pointer-events: none;"
                                            role="tab">
                                            <div class="product__thumb__pic set-bg"
                                                data-setbg="{{ Storage::url($image) }}">
                                            </div>
                                        </span>
                                    @else
                                        <a class="nav-link" data-toggle="tab" href="#tabs-{{ $loop->index + 2 }}"
                                            role="tab">
                                            <div class="product__thumb__pic set-bg"
                                                data-setbg="{{ Storage::url($image) }}">
                                            </div>
                                        </a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-lg-6 col-md-9">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="product__details__pic__item" style="position: relative;">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    @if (($product->stock_quantity ?? 0) == 0)
                                        <div
                                            style="position: absolute; top: 20px; right: 20px; background-color: rgba(220, 53, 69, 0.95); color: white; padding: 12px 20px; border-radius: 5px; font-weight: bold; font-size: 14px; text-transform: uppercase; z-index: 10; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                                            Out of Stock
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @foreach ($product->additional_images ?? [] as $image)
                                <div class="tab-pane" id="tabs-{{ $loop->index + 2 }}" role="tabpanel">
                                    <div class="product__details__pic__item">
                                        <img src="{{ Storage::url($image) }}" alt="{{ $product->name }}">
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

                            @if (
                                ($product->has_size_options && !empty($product->size_options)) ||
                                    ($product->has_color_options && !empty($product->color_options)))
                                <div class="product__details__option">
                                    @if ($product->has_size_options && !empty($product->size_options))
                                        <div class="product__details__option__size">
                                            <span>Size:</span>
                                            @php
                                                // Ensure size_options is an array
                                                $sizeOptions = is_array($product->size_options)
                                                    ? $product->size_options
                                                    : [];
                                                // If it's a JSON string, decode it
                                                if (is_string($product->size_options)) {
                                                    $decoded = json_decode($product->size_options, true);
                                                    $sizeOptions = is_array($decoded) ? $decoded : [];
                                                }
                                            @endphp
                                            @foreach ($sizeOptions as $index => $sizeOption)
                                                @php
                                                    $sizeName = is_array($sizeOption)
                                                        ? $sizeOption['name'] ?? ''
                                                        : $sizeOption;
                                                    $sizeId = 'size-' . $product->id . '-' . $index;
                                                @endphp
                                                @if (!empty($sizeName))
                                                    <label for="{{ $sizeId }}"
                                                        class="{{ $selectedSize === $sizeName ? 'active' : '' }}">
                                                        {{ $sizeName }}
                                                        <input type="radio" id="{{ $sizeId }}"
                                                            wire:model="selectedSize" value="{{ $sizeName }}"
                                                            name="size-{{ $product->id }}">
                                                    </label>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif

                                    @if ($product->has_color_options && !empty($product->color_options))
                                        <div class="product__details__option__color">
                                            <span>Color:</span>
                                            @php
                                                // Ensure color_options is an array
                                                $colorOptions = is_array($product->color_options)
                                                    ? $product->color_options
                                                    : [];
                                                // If it's a JSON string, decode it
                                                if (is_string($product->color_options)) {
                                                    $decoded = json_decode($product->color_options, true);
                                                    $colorOptions = is_array($decoded) ? $decoded : [];
                                                }
                                            @endphp
                                            {{-- @foreach ($colorOptions as $index => $colorOption)
                                                @php
                                                    // Extract color name from the data structure
                                                    if (is_array($colorOption)) {
                                                        $colorName = $colorOption['name'] ?? '';
                                                    } else {
                                                        $colorName = $colorOption;
                                                    }
                                                @endphp
                                                @if (!empty($colorName))
                                                    <label for="sp-{{ $index }}"
                                                        style="background: #{{ $colorName }}">
                                                        <input type="radio" id="sp-{{ $index }}"
                                                            wire:model="selectedColor"
                                                            value="{{ $colorName }}"
                                                            name="color-{{ $product->id }}">
                                                    </label>
                                                @endif
                                            @endforeach --}}
                                            @foreach ($colorOptions as $index => $colorOption)
                                                @php
                                                    $colorName = is_array($colorOption)
                                                        ? $colorOption['name'] ?? ''
                                                        : $colorOption;
                                                    $isActive = $selectedColor === $colorName;
                                                @endphp

                                                @if (!empty($colorName))
                                                    <label for="sp-{{ $index }}"
                                                        class="color-swatch {{ $isActive ? 'active' : '' }}"
                                                        style="background: #{{ $colorName }}; position: relative;">
                                                        <input type="radio" id="sp-{{ $index }}"
                                                            wire:model="selectedColor" value="{{ $colorName }}"
                                                            name="color-{{ $product->id }}">
                                                        @if ($isActive)
                                                            <i class="fa fa-check"
                                                                style="position: absolute; color: #e53637; font-size: 14px; font-weight: bold; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;"></i>
                                                        @endif
                                                    </label>
                                                @endif
                                            @endforeach

                                        </div>
                                    @endif
                                </div>
                            @endif

                            <div class="product__details__cart__option">
                                @if (($product->stock_quantity ?? 0) == 0)
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
                                        @if (($product->stock_quantity ?? 0) == 0)
                                            <span style="color: #dc3545; font-weight: bold;">Out of Stock</span>
                                        @else
                                            {{ $product->stock_quantity }} available
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
                                                    @if (($product->stock_quantity ?? 0) == 0)
                                                        <span style="color: #dc3545; font-weight: bold;">Out of
                                                            Stock</span>
                                                    @else
                                                        {{ $product->stock_quantity }}
                                                    @endif
                                                </li>
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
    </style>
</div>

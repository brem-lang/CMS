<div>
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Shop</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <span>Shop</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shop Section Begin -->
    <section class="shop spad">
        <div class="container">
            <div class="row" style="margin-top:-50px;">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Love and Respect Collection</h2>
                        <h5 class="mt-4 text-secondary">
                            The Love and Respect Collection is more than just apparel and accessories, it’s a lifestyle
                            statement. Each piece is designed to inspire kindness, unity, and positivity, reminding us
                            of the values that matter most.
                        </h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <div class="shop__sidebar">
                        <div class="shop__sidebar__search">
                            <form wire:submit.prevent>
                                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search...">
                                <button type="submit"><span class="icon_search"></span></button>
                            </form>
                        </div>
                        <div class="shop__sidebar__accordion">
                            <div class="accordion" id="accordionExample">
                                <div class="card">
                                    <div class="card-heading">
                                        <h6 style="margin-bottom: 15px; font-weight: 600;">Filter Price</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="shop__sidebar__price">
                                            <ul>
                                                <li>
                                                    <a href="#" wire:click.prevent="$set('priceRange', '0-50')"
                                                        class="{{ $priceRange === '0-50' ? 'active' : '' }}"
                                                        style="cursor: pointer;">₱0.00 - ₱50.00</a>
                                                </li>
                                                <li>
                                                    <a href="#" wire:click.prevent="$set('priceRange', '50-100')"
                                                        class="{{ $priceRange === '50-100' ? 'active' : '' }}"
                                                        style="cursor: pointer;">₱50.00 - ₱100.00</a>
                                                </li>
                                                <li>
                                                    <a href="#" wire:click.prevent="$set('priceRange', '100-150')"
                                                        class="{{ $priceRange === '100-150' ? 'active' : '' }}"
                                                        style="cursor: pointer;">₱100.00 - ₱150.00</a>
                                                </li>
                                                <li>
                                                    <a href="#" wire:click.prevent="$set('priceRange', '150-200')"
                                                        class="{{ $priceRange === '150-200' ? 'active' : '' }}"
                                                        style="cursor: pointer;">₱150.00 - ₱200.00</a>
                                                </li>
                                                <li>
                                                    <a href="#" wire:click.prevent="$set('priceRange', '200-250')"
                                                        class="{{ $priceRange === '200-250' ? 'active' : '' }}"
                                                        style="cursor: pointer;">₱200.00 - ₱250.00</a>
                                                </li>
                                                <li>
                                                    <a href="#" wire:click.prevent="$set('priceRange', '250+')"
                                                        class="{{ $priceRange === '250+' ? 'active' : '' }}"
                                                        style="cursor: pointer;">₱250.00+</a>
                                                </li>
                                                @if ($priceRange)
                                                    <li>
                                                        <a href="#" wire:click.prevent="clearFilters"
                                                            style="cursor: pointer; color: #dc3545;">Clear Filter</a>
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
                <div class="col-lg-9">
                    <div class="shop__product__option">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__left">
                                    <p>Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of
                                        {{ $products->total() }} {{ Str::plural('result', $products->total()) }}</p>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__right">
                                    <p>Sort by:</p>
                                    <select wire:model.live="sortBy" data-livewire-select
                                        style="padding: 5px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; width: 200px;">
                                        <option value="name">Name (A-Z)</option>
                                        <option value="price_low">Price: Low to High</option>
                                        <option value="price_high">Price: High to Low</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @forelse($products as $product)
                            <div class="col-lg-4 col-md-6 col-sm-6" wire:key="product-{{ $product->id }}">
                                <div class="product__item">
                                    <div class="product__item__pic set-bg rounded shadow-sm border-0 d-flex align-items-center justify-content-center"
                                        data-setbg="{{ $product->image_url }}"
                                        style="background-image: url('{{ $product->image_url }}'); position: relative; {{ ($product->stock_quantity ?? 0) == 0 ? 'opacity: 0.5;' : '' }}"
                                        wire:click="selectProduct({{ $product->id }})"
                                        onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.classList.add('border', 'border-primary')"
                                        onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.classList.remove('border', 'border-primary')">

                                        @if(($product->stock_quantity ?? 0) == 0)
                                            <div style="position: absolute; top: 10px; right: 10px; background-color: rgba(220, 53, 69, 0.95); color: white; padding: 8px 15px; border-radius: 5px; font-weight: bold; font-size: 12px; text-transform: uppercase; z-index: 10; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
                                                Out of Stock
                                            </div>
                                        @endif

                                        <div class="opacity-0 hover-show d-none d-md-block">
                                            <button class="btn btn-light btn-sm shadow-sm rounded-pill px-3">
                                                Quick View
                                            </button>
                                        </div>
                                    </div>
                                    <div class="product__item__text">
                                        <h6>{{ $product->name }}</h6>
                                        @if(($product->stock_quantity ?? 0) == 0)
                                            <a href="#" class="add-cart" style="opacity: 0.5; cursor: not-allowed; pointer-events: none;" onclick="return false;">
                                                + Add To Cart
                                            </a>
                                        @else
                                            <a href="#" wire:click.prevent="addToCart({{ $product->id }})"
                                                class="add-cart">
                                                + Add To Cart
                                            </a>
                                        @endif
                                        <div class="rating">
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                            <i class="fa fa-star-o"></i>
                                        </div>
                                        <h5>₱{{ number_format($product->price, 2) }}</h5>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-lg-12">
                                <div class="text-center py-5">
                                    <p>No products found matching your criteria.</p>
                                    @if ($search || $priceRange)
                                        <a href="#" wire:click="clearFilters" class="primary-btn">Clear
                                            Filters</a>
                                    @endif
                                </div>
                            </div>
                        @endforelse
                    </div>
                    @if ($products->hasPages())
                        <div class="row">
                            <div class="col-lg-12">
                                {{ $products->links('vendor.livewire.custom-shop') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Section End -->
</div>

<script>
    // Function to set background images
    function setBackgroundImages() {
        $('.set-bg').each(function() {
            var bg = $(this).data('setbg');
            var $el = $(this);
            if (bg) {
                // Get existing inline styles (excluding background-image)
                var existingStyle = $el.attr('style') || '';
                // Remove any existing background-image from style
                existingStyle = existingStyle.replace(/background-image\s*:\s*[^;]+;?/gi, '').trim();
                // Add background-image to the style
                var newStyle = existingStyle + (existingStyle ? ' ' : '') + 'background-image: url(' + bg +
                    ');';
                $el.attr('style', newStyle);
            }
        });
    }

    // Ensure nice-select doesn't interfere with Livewire select
    document.addEventListener('livewire:init', () => {
        // Destroy nice-select if it was initialized on our Livewire select
        const livewireSelect = document.querySelector('[data-livewire-select]');
        if (livewireSelect && livewireSelect.nextElementSibling && livewireSelect.nextElementSibling.classList
            .contains('nice-select')) {
            $(livewireSelect).niceSelect('destroy');
        }
        // Set background images on init
        setBackgroundImages();
    });

    // Re-destroy nice-select and re-set background images after Livewire updates
    document.addEventListener('livewire:update', () => {
        const livewireSelect = document.querySelector('[data-livewire-select]');
        if (livewireSelect && livewireSelect.nextElementSibling && livewireSelect.nextElementSibling.classList
            .contains('nice-select')) {
            $(livewireSelect).niceSelect('destroy');
        }
        // Re-set background images after DOM update
        setBackgroundImages();
    });
</script>

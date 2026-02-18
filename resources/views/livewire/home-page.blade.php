<div>
    <!-- Hero Section Begin -->
    <section class="hero" wire:ignore>
        <div class="hero__container" style="position: relative; overflow: hidden; height: 500px;">
            <video autoplay muted loop playsinline width="100%"
                style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0; display: block; visibility: visible; opacity: 1;">
                <source src="{{ asset('videos/Brader-Skate.mp4') }}" type="video/mp4">
            </video>
            <div class="container" style="position: relative; z-index: 1; height: 100%;">
                <div class="row h-100">
                    <div class="col-xl-5 col-lg-7 col-md-8 d-flex align-items-center">
                        <div class="hero__text" style="position: relative; z-index: 2;">
                            <h2
                                style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); opacity: 1 !important; top: 0 !important; position: relative !important; font-weight: 800; line-height: 1.2;">
                                CRIST BRIAND
                                <br>
                                <span style="font-size: 24px; font-weight: 400; display: inline-block; width: 100%;">
                                    Comedic content + brand collabs that make people smile and buy.
                                </span>
                            </h2>
                            <a href="/shop"
                                class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all text-white"
                                style="opacity: 1 !important; top: 0 !important; position: relative !important; color: white !important;"
                                onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                                onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">

                                Shop Merch
                                <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                            </a>
                            <br>
                            <br>
                            <a href="/digital-products"
                                class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all text-white"
                                style="opacity: 1 !important; top: 0 !important; position: relative !important; color: white !important;"
                                onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                                onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">

                                Digital Products
                                <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- BUenas Section Begin -->
    <section class="services spad" style="margin-top:-80px;">
        <div class="container">
            <!-- Desktop Layout -->
            <div class="row align-items-center d-none d-md-flex">
                <div class="col-lg-12">
                    <div class="card shadow-lg border-0 p-5"
                        style="border-radius: 16px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); border: 1px solid #e9ecef;">
                        <div class="row align-items-center">
                            <div class="col-lg-3 text-center">
                                <img src="{{ asset('img/buenas_logo.png') }}" alt="Buenas" class="img-fluid"
                                    style="max-height: 160px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));">
                            </div>
                            <div class="col-lg-5">
                                <h2 class="text-dark mb-3" style="font-weight: 800; font-size: 2rem; line-height: 1.2;">
                                    <strong
                                        style="background: linear-gradient(135deg, #111111 0%, #333333 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">Play
                                        Smart. Win Big.</strong>
                                </h2>
                                <p class="text-secondary mb-0"
                                    style="font-size: 1.05rem; line-height: 1.7; color: #6c757d;">
                                    Official online gaming partner. Enjoy exclusive access through my link.
                                </p>
                            </div>
                            <div class="col-lg-4 text-lg-end text-center">
                                <a href="https://bit.ly/CristBriand-buenasph" target="_blank" rel="noopener noreferrer"
                                    class="primary-btn d-inline-block text-decoration-none shadow-lg transition-all"
                                    style="font-size: 14px; padding: 14px 32px; background: linear-gradient(135deg, #000000 0%, #333333 100%); border-radius: 8px; font-weight: 700; letter-spacing: 1px;"
                                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.2)'; this.querySelector('.arrow_right').classList.add('ms-3')"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'; this.querySelector('.arrow_right').classList.remove('ms-3')">
                                    Play on Buenas
                                    <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                                </a>
                                <div class="mt-3">
                                    <small class="text-muted" style="font-size: 0.75rem; font-weight: 500;">18+ only.
                                        Please play responsibly.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile Layout -->
            <div class="row d-block d-md-none">
                <div class="col-12">
                    <div class="card shadow-lg border-0 p-2"
                        style="border-radius: 16px; background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); border: 1px solid #e9ecef;">
                        <div class="text-center">
                            <img src="{{ asset('img/buenas_logo.png') }}" alt="Buenas" class="img-fluid"
                                style="max-height: 60px; filter: drop-shadow(0 4px 8px rgba(0,0,0,0.1));">
                        </div>
                        <div class="text-center" style="margin-top: -12px;margin-bottom: 11px;">
                            <h3 style="font-weight: 800; color: #111111; font-size: 1.2rem;">
                                <strong>Play Smart. Win Big.</strong>
                            </h3>
                            <p class="text-secondary mb-0" style="font-size: 11px;line-height: 0.8;margin-top:6px;">
                                Official online gaming partner. Enjoy exclusive access through my link.
                            </p>
                        </div>
                        <div class="text-center">
                            <a href="https://bit.ly/CristBriand-buenasph" target="_blank" rel="noopener noreferrer"
                                class="primary-btn d-inline-block text-decoration-none shadow-lg transition-all"
                                style="font-size: 12px; padding: 10px 24px; background: linear-gradient(135deg, #000000 0%, #333333 100%); border-radius: 8px; font-weight: 700; letter-spacing: 0.5px;"
                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(0,0,0,0.2)'; this.querySelector('.arrow_right').classList.add('ms-3')"
                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.15)'; this.querySelector('.arrow_right').classList.remove('ms-3')">
                                Play on Buenas
                                <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                            </a>
                            <p class="text-muted small mt-2 mb-0"
                                style="font-weight: 500; font-size: 11px;margin-top:-20px;">18+ only.
                                Please play responsibly.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- BUenas Section End -->

    <!-- Product Section Begin -->
    {{-- style="margin-top: -60px --}}
    <section class="product spad" style="margin-top:-80px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Merch</h2>
                        <h5 class="mt-4 text-secondary">
                            A blend of purpose and style — our Love and Respect merch line is designed to remind you of
                            what truly matters.
                            From the classic <strong class="font-bold">Bracelet</strong> that carries meaning, to the
                            minimalist <strong class="font-bold">Shirt</strong> and
                            everyday <strong class="font-bold">Tote Bag</strong>, each piece is crafted to inspire
                            self-worth, peace, and connection.
                        </h5>

                        <h5 class="mt-4 text-secondary">✨ Wear the message. Carry the energy. Spread love and respect.
                        </h5>
                    </div>
                </div>
            </div>
            <!-- Desktop Layout -->
            <div class="row product__filter d-none d-md-flex">
                @foreach ($products as $product)
                    <div class="col-lg-3 col-md-6 mix new-arrivals" wire:key="product-desktop-{{ $product->id }}">
                        <div class="product__item">
                            <div class="product__item__pic set-bg rounded shadow-sm border-0 d-flex align-items-center justify-content-center"
                                data-setbg="{{ $product->image_url }}"
                                style="background-image: url('{{ $product->image_url }}'); position: relative; {{ ($product->stock_quantity ?? 0) == 0 ? 'opacity: 0.5;' : '' }}"
                                wire:click="selectProduct({{ $product->id }})"
                                onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.classList.add('border', 'border-primary')"
                                onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.classList.remove('border', 'border-primary')">

                                @if ($product->badge === 'best_seller')
                                    <div
                                        style="position: absolute; top: 10px; right: 10px; background-color: rgba(255, 193, 7, 0.95); color: #000; padding: 6px 12px; border-radius: 5px; font-weight: bold; font-size: 11px; text-transform: uppercase; z-index: 10; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
                                        Best Seller
                                    </div>
                                @elseif ($product->badge === 'limited')
                                    <div
                                        style="position: absolute; top: 10px; right: 10px; background-color: rgba(220, 53, 69, 0.95); color: white; padding: 6px 12px; border-radius: 5px; font-weight: bold; font-size: 11px; text-transform: uppercase; z-index: 10; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
                                        Limited
                                    </div>
                                @endif

                                @if (($product->stock_quantity ?? 0) == 0)
                                    <div
                                        style="position: absolute; top: 10px; @if ($product->badge) left: 10px; @else right: 10px; @endif background-color: rgba(220, 53, 69, 0.95); color: white; padding: 8px 15px; border-radius: 5px; font-weight: bold; font-size: 12px; text-transform: uppercase; z-index: 10; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
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
                                <!-- <a href="#" class="add-cart" wire:click.prevent="addToCart({{ $product->id }})">+ Add To Cart</a> -->
                                {{-- <div class="rating">
                                    <i class="fa fa-star-o"></i>
                                    <i class="fa fa-star-o"></i>
                                    <i class="fa fa-star-o"></i>
                                    <i class="fa fa-star-o"></i>
                                    <i class="fa fa-star-o"></i>
                                </div> --}}
                                <h5>₱{{ number_format($product->price, 2) }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Mobile Layout - One product only -->
            <div class="row d-block d-md-none">
                @if ($products->isNotEmpty())
                    @php $firstProduct = $products->first(); @endphp
                    <div class="col-12 mb-4" wire:key="product-mobile-{{ $firstProduct->id }}">
                        <div class="product__item">
                            <div class="product__item__pic set-bg rounded shadow-sm border-0 d-flex align-items-center justify-content-center"
                                data-setbg="{{ $firstProduct->image_url }}"
                                style="background-image: url('{{ $firstProduct->image_url }}'); position: relative; min-height: 350px; {{ ($firstProduct->stock_quantity ?? 0) == 0 ? 'opacity: 0.5;' : '' }}"
                                wire:click="selectProduct({{ $firstProduct->id }})">

                                @if ($firstProduct->badge === 'best_seller')
                                    <div
                                        style="position: absolute; top: 10px; right: 10px; background-color: rgba(255, 193, 7, 0.95); color: #000; padding: 6px 12px; border-radius: 5px; font-weight: bold; font-size: 11px; text-transform: uppercase; z-index: 10; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
                                        Best Seller
                                    </div>
                                @elseif ($firstProduct->badge === 'limited')
                                    <div
                                        style="position: absolute; top: 10px; right: 10px; background-color: rgba(220, 53, 69, 0.95); color: white; padding: 6px 12px; border-radius: 5px; font-weight: bold; font-size: 11px; text-transform: uppercase; z-index: 10; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
                                        Limited
                                    </div>
                                @endif

                                @if (($firstProduct->stock_quantity ?? 0) == 0)
                                    <div
                                        style="position: absolute; top: 10px; @if ($firstProduct->badge) left: 10px; @else right: 10px; @endif background-color: rgba(220, 53, 69, 0.95); color: white; padding: 8px 15px; border-radius: 5px; font-weight: bold; font-size: 12px; text-transform: uppercase; z-index: 10; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
                                        Out of Stock
                                    </div>
                                @endif
                            </div>
                            <div class="product__item__text text-center mt-3">
                                <h6>{{ $firstProduct->name }}</h6>
                                <p class="text-secondary small mb-2" style="font-size: 13px; line-height: 1.4;">
                                    {{ Str::limit($firstProduct->description ?? '', 80) }}
                                </p>
                                <h5>₱{{ number_format($firstProduct->price, 2) }}</h5>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <!-- Desktop Button -->
                    <a href="/shop"
                        class="primary-btn d-none d-md-inline-block text-decoration-none shadow-sm transition-all"
                        onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                        onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">
                        Explore Collection
                        <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                    </a>
                    <!-- Mobile Button -->
                    <a href="/shop"
                        class="primary-btn d-inline-block d-md-none text-decoration-none shadow-sm transition-all"
                        onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                        onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">
                        Shop Merch
                        <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->

    <!-- Digital Products Section Begin -->
    <section class="product spad" style="margin-top: -30px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Digital Products</h2>
                        <h5 class="mt-4 text-secondary">
                            Instant access to the tools behind the smiles <strong>(and the sales)</strong>.
                        </h5>
                    </div>
                </div>
            </div>
            <!-- Desktop Layout -->
            <div class="row product__filter d-none d-md-flex">
                @foreach ($digitalProducts as $product)
                    <div class="col-lg-3 col-md-6 mix new-arrivals"
                        wire:key="digital-product-desktop-{{ $product->id }}">
                        <div class="product__item">
                            <div class="product__item__pic set-bg rounded shadow-sm border-0 d-flex align-items-center justify-content-center position-relative"
                                @if ($product->thumbnail_url) data-setbg="{{ $product->thumbnail_url }}"
                            style="background-image: url('{{ $product->thumbnail_url }}'); min-height: 220px; background-size: cover; background-position: center; position: relative;"
                            @else
                            style="min-height: 220px; align-items: center; justify-content: center; position: relative;" @endif
                                wire:click="selectDigitalProduct({{ $product->id }})"
                                onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.classList.add('border', 'border-primary')"
                                onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.classList.remove('border', 'border-primary')">

                                @if (!$product->thumbnail_url)
                                    <span class="text-muted"><i class="fa fa-file-o fa-3x"></i></span>
                                @endif

                                <div class="opacity-0 hover-show d-none d-md-block">
                                    <button class="btn btn-light btn-sm shadow-sm rounded-pill px-3">
                                        Quick View
                                    </button>
                                </div>

                                @if ($product->file_type)
                                    <div
                                        style="position: absolute; top: 10px; left: 10px; background-color: rgba(0, 0, 0, 0.7); color: white; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase; z-index: 10;">
                                        {{ strtoupper($product->file_type) }}
                                    </div>
                                @endif
                            </div>
                            <div class="product__item__text">
                                <h6>{{ Str::limit($product->title, 30) }}</h6>
                                <p class="text-secondary small mb-2" style="font-size: 12px; line-height: 1.4;">
                                    {{ Str::limit(strip_tags($product->description ?? ''), 60) }}
                                </p>
                                <h5>{{ $product->is_free ? 'Free' : '₱' . number_format($product->price, 2) }}</h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Mobile Layout - One product only -->
            <div class="row d-block d-md-none">
                @if ($digitalProducts->isNotEmpty())
                    @php $firstDigitalProduct = $digitalProducts->first(); @endphp
                    <div class="col-12 mb-4" wire:key="digital-product-mobile-{{ $firstDigitalProduct->id }}">
                        <div class="product__item">
                            <div class="product__item__pic set-bg rounded shadow-sm border-0 d-flex align-items-center justify-content-center position-relative"
                                @if ($firstDigitalProduct->thumbnail_url) data-setbg="{{ $firstDigitalProduct->thumbnail_url }}"
                            style="background-image: url('{{ $firstDigitalProduct->thumbnail_url }}'); min-height: 350px; background-size: cover; background-position: center; position: relative;"
                            @else
                            style="min-height: 350px; align-items: center; justify-content: center; position: relative;" @endif
                                wire:click="selectDigitalProduct({{ $firstDigitalProduct->id }})">

                                @if (!$firstDigitalProduct->thumbnail_url)
                                    <span class="text-muted"><i class="fa fa-file-o fa-4x"></i></span>
                                @endif

                                @if ($firstDigitalProduct->file_type)
                                    <div
                                        style="position: absolute; top: 10px; left: 10px; background-color: rgba(0, 0, 0, 0.7); color: white; padding: 6px 12px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase; z-index: 10;">
                                        {{ strtoupper($firstDigitalProduct->file_type) }}
                                    </div>
                                @endif
                            </div>
                            <div class="product__item__text text-center mt-3">
                                <h6>{{ $firstDigitalProduct->title }}</h6>
                                <p class="text-secondary small mb-2" style="font-size: 13px; line-height: 1.4;">
                                    {{ Str::limit(strip_tags($firstDigitalProduct->description ?? ''), 80) }}
                                </p>
                                <h5>{{ $firstDigitalProduct->is_free ? 'Free' : '₱' . number_format($firstDigitalProduct->price, 2) }}
                                </h5>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <!-- Desktop Button -->
                    <a href="{{ route('digital-products') }}"
                        class="primary-btn d-none d-md-inline-block text-decoration-none shadow-sm transition-all"
                        onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                        onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">
                        See All Digital Products
                        <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                    </a>
                    <!-- Mobile Button -->
                    <a href="{{ route('digital-products') }}"
                        class="primary-btn d-inline-block d-md-none text-decoration-none shadow-sm transition-all"
                        onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                        onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">
                        See All Digital Products
                        <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- Digital Products Section End -->

    <!-- Latest Blog Section Begin -->
    <section class="latest spad" style="margin-top:-130px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Blogs</h2>
                        <h5 class="mt-4 text-secondary">
                            Crist Briand seems to embrace a philosophy of authenticity and spreading positivity,
                            particularly through his creative and spiritual content.
                        </h5>
                    </div>
                </div>
            </div>
            <!-- Desktop Layout: 4 blogs in one row -->
            <div class="row d-none d-md-flex">
                @foreach ($blogs as $blog)
                    <div class="col-lg-3 col-md-3" wire:key="blog-desktop-{{ $blog->id }}">
                        <div class="blog__item">
                            <div class="blog__item__pic set-bg" data-setbg="{{ $blog->image_url }}"
                                style="background-image: url('{{ $blog->image_url }}');">
                            </div>
                            <div class="blog__item__text">
                                <span style="color: #666666;"><img
                                        src="{{ asset('bootstrap/img/icon/calendar.png') }}" alt="">
                                    {{ $blog->created_at->format('d F Y') }}</span>
                                <h5 style="color: #333333;">{{ $blog->title }}</h5>
                                <a href="javascript:void(0)" wire:click.prevent="openBlog({{ $blog->id }})"
                                    class="text-primary fw-bold text-decoration-none shadow-hover"
                                    style="color: #007bff;">
                                    Read More
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Mobile Layout: 1 blog only -->
            <div class="row d-block d-md-none">
                @if ($blogs->isNotEmpty())
                    @php $firstBlog = $blogs->first(); @endphp
                    <div class="col-12" wire:key="blog-mobile-{{ $firstBlog->id }}">
                        <div class="blog__item">
                            <div class="blog__item__pic set-bg" data-setbg="{{ $firstBlog->image_url }}"
                                style="background-image: url('{{ $firstBlog->image_url }}'); min-height: 300px;">
                            </div>
                            <div class="blog__item__text">
                                <span style="color: #666666;"><img
                                        src="{{ asset('bootstrap/img/icon/calendar.png') }}" alt="">
                                    {{ $firstBlog->created_at->format('d F Y') }}</span>
                                <h5 style="color: #333333;">{{ $firstBlog->title }}</h5>
                                <a href="javascript:void(0)" wire:click.prevent="openBlog({{ $firstBlog->id }})"
                                    class="text-primary fw-bold text-decoration-none shadow-hover"
                                    style="color: #007bff;">
                                    Read More
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <!-- Latest Blog Section End -->

    <!-- Work With Me Section Begin -->
    <section class="services spad" style="margin-top: -160px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Work With Me</h2>
                    </div>
                </div>
            </div>

            <!-- Desktop Layout: 2x2 Grid -->
            <div class="row d-none d-md-flex">
                <!-- Card 1: Brand Collabs -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border h-100 p-4" style="border-radius: 8px;">
                        <h4 class="mb-3" style="font-weight: 700;">Brand Collabs</h4>
                        <p class="text-secondary mb-3">Creative partnerships that bring your brand story to life
                            through engaging content.</p>
                        <a href="{{ route('contact') }}"
                            class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all"
                            onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                            onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">
                            Get Started
                            <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                        </a>
                    </div>
                </div>
                <!-- Card 2: Promo Skits -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border h-100 p-4" style="border-radius: 8px;">
                        <h4 class="mb-3" style="font-weight: 700;">Promo Skits</h4>
                        <p class="text-secondary mb-3">Fun, memorable promotional content that connects with your
                            audience and drives sales.</p>
                        <a href="{{ route('contact') }}"
                            class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all"
                            onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                            onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">
                            Get Started
                            <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                        </a>
                    </div>
                </div>
                <!-- Card 3: 1-on-1 Call -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border h-100 p-4" style="border-radius: 8px;">
                        <h4 class="mb-3" style="font-weight: 700;">1-on-1 Call</h4>
                        <p class="text-secondary mb-3">Personalized consultation to discuss your project and goals.</p>
                        <a href="{{ route('contact') }}"
                            class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all"
                            onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                            onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">
                            Get Started
                            <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                        </a>
                    </div>
                </div>
                <!-- Card 4: Appearances & Bookings -->
                <div class="col-lg-6 mb-4">
                    <div class="card shadow-sm border h-100 p-4" style="border-radius: 8px;">
                        <h4 class="mb-3" style="font-weight: 700;">Appearances & Bookings</h4>
                        <p class="text-secondary mb-3">Book me for events, appearances, or special collaborations.</p>
                        <a href="{{ route('contact') }}"
                            class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all"
                            onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                            onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">
                            Get Started
                            <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mobile Layout: Simplified List -->
            <div class="row d-block d-md-none">
                <div class="col-12">
                    <div class="card shadow-sm border p-4" style="border-radius: 8px;">
                        <ul class="list-unstyled mb-4">
                            <li class="mb-3">
                                <strong>Brand Collabs</strong>
                                <p class="text-secondary small mb-0">Creative partnerships that bring your brand story
                                    to life.</p>
                            </li>
                            <li class="mb-3">
                                <strong>Promo Skits</strong>
                                <p class="text-secondary small mb-0">Fun, memorable promotional content that connects.
                                </p>
                            </li>
                            <li class="mb-3">
                                <strong>1-on-1 Call</strong>
                                <p class="text-secondary small mb-0">Personalized consultation for your project.</p>
                            </li>
                            <li class="mb-3">
                                <strong>Appearances & Bookings</strong>
                                <p class="text-secondary small mb-0">Book me for events and special collaborations.</p>
                            </li>
                        </ul>
                        <div class="text-center">
                            <a href="{{ route('contact') }}"
                                class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all"
                                onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                                onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">
                                Work With Me →
                                <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Work With Me Section End -->

    <!-- About Me Section Begin -->
    <section class="services spad" style="margin-top: -160px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto text-center">
                    <div class="section-title">
                        <h2>About Me</h2>
                    </div>
                    <p class="text-secondary mb-4" style="font-size: 1.1em; line-height: 1.8; text-align: justify;">
                        Hi, I'm <strong>Briand</strong> — a <strong>content creator, comedian, and
                            freedom-chaser</strong>.
                        I make people laugh, think, and sometimes dance (not always in that order).
                        My content is all about <strong>authentic expression</strong>, playful humor, and seeing life
                        from a
                        fresh perspective. What started as spontaneous skits and pranks has now grown into a
                        <strong>community of hundreds of thousands</strong> who enjoy my take on everyday life.
                        I believe in turning ordinary moments into <strong>extraordinary stories</strong> — with a
                        little mischief, wisdom, and laughter along the way.
                    </p>
                    <a href="{{ route('about') }}"
                        class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all"
                        onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                        onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">
                        Learn More
                        <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- About Me Section End -->

    <!-- Contact Section Begin -->
    <section class="services spad" style="margin-top: -130px; margin-bottom: 20px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Contact</h2>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-10 col-md-12">
                    <div class="row mb-4">
                        <!-- Location Column -->
                        <div class="col-lg-4 col-md-4 col-sm-12 mb-4 mb-md-0">
                            <div class="d-flex align-items-start justify-content-center">
                                <i class="fa fa-map-marker"
                                    style="font-size: 24px; color: #e53637; margin-top: 5px; margin-right: 15px;"></i>
                                <div class="text-center">
                                    <p class="mb-0 text-secondary" style="text-align: center;">Milagros Building
                                        Ilustre St., Davao City<br>Hannah's Pawnshop</p>
                                </div>
                            </div>
                        </div>
                        <!-- Phone Column -->
                        <div class="col-lg-4 col-md-4 col-sm-12 mb-4 mb-md-0">
                            <div class="d-flex align-items-start justify-content-center">
                                <i class="fa fa-phone"
                                    style="font-size: 24px; color: #e53637; margin-top: 5px; margin-right: 15px;"></i>
                                <div class="text-center">
                                    <p class="mb-0 text-secondary" style="text-align: center;">+639 995 234 1590</p>
                                </div>
                            </div>
                        </div>
                        <!-- Email Column -->
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <div class="d-flex align-items-start justify-content-center">
                                <i class="fa fa-envelope"
                                    style="font-size: 24px; color: #e53637; margin-top: 5px; margin-right: 15px;"></i>
                                <div class="text-center">
                                    <p class="mb-0 text-secondary" style="text-align: center;">cboncada@gmail.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Social Icons -->
                    <div class="text-center">
                        <div class="hero__social mt-4 d-flex justify-content-center">
                            <a href="https://www.youtube.com/@cristbriand3086" target="_blank"
                                rel="noopener noreferrer" class="me-3"
                                style="font-size: 24px; color: #333; transition: color 0.3s;"
                                onmouseover="this.style.color='#e53637'" onmouseout="this.style.color='#333'">
                                <i class="fa fa-youtube"></i>
                            </a>
                            <a href="https://www.facebook.com/cristbriand.brader" target="_blank"
                                rel="noopener noreferrer" class="me-3"
                                style="font-size: 24px; color: #333; transition: color 0.3s;"
                                onmouseover="this.style.color='#e53637'" onmouseout="this.style.color='#333'">
                                <i class="fa fa-facebook"></i>
                            </a>
                            <a href="http://instagram.com/crist.briand" target="_blank" rel="noopener noreferrer"
                                class="me-3" style="font-size: 24px; color: #333; transition: color 0.3s;"
                                onmouseover="this.style.color='#e53637'" onmouseout="this.style.color='#333'">
                                <i class="fa fa-instagram"></i>
                            </a>
                            <a href="https://www.tiktok.com/@crist.briand" target="_blank" rel="noopener noreferrer"
                                style="font-size: 24px; color: #333; transition: color 0.3s;"
                                onmouseover="this.style.color='#e53637'" onmouseout="this.style.color='#333'">
                                <i class="fa fa-video-camera"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Section End -->

    <!-- Services Section Begin -->
    <!-- <section class="services spad" style="margin-top:-80px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Services</h2>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="services__image">
                        <img src="{{ asset('img/services.webp') }}" alt="Services" class="img-fluid">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="services__content">
                        <h5 class="text-secondary" style="text-align: justify;">
                            I <span class="text-secondary"><strong>bring your brand to life</strong></span> through
                            creative
                            storytelling and engaging digital content.
                            Whether it’s showcasing unique flavors or highlighting essential services, my focus is on
                            <span class="text-secondary"><strong class="text-secondary">building genuine
                                    connections</strong></span>.
                            I don’t just show what you offer—I make sure your brand reaches the right audience and
                            <span class="text-secondary"><strong class="text-secondary">leaves a lasting
                                    impression</strong></span>.
                        </h5>
                    </div>
                    <div class="text-center mt-3">
                        <a href="/contact"
                            class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all"
                            onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                            onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">
                            Let Us Know
                            <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <!-- Services Section End -->

    <style>
        /* Responsive margin for product section */
        .product-section-margin {
            margin-top: 10px;
        }

        @media (min-width: 768px) {
            .product-section-margin {
                margin-top: -60px;
            }
        }

        /* Responsive hero video height */
        .hero__container {
            height: 450px;
        }

        @media (min-width: 576px) and (max-width: 767px) {
            .hero__container {
                height: 550px;
            }
        }

        @media (min-width: 768px) {
            .hero__container {
                height: 800px;
            }
        }
    </style>
</div>

<script>
    // Video initialization - no carousel needed
    (function() {
        function initHeroVideo() {
            var heroContainer = document.querySelector('.hero__container');
            var video = heroContainer ? heroContainer.querySelector('video') : null;
            if (video) {
                video.setAttribute('data-protected', 'true');
            }
        }

        // Run immediately
        initHeroVideo();

        // Also run when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initHeroVideo);
        } else {
            initHeroVideo();
        }

        // Also run on window load
        window.addEventListener('load', function() {
            setTimeout(initHeroVideo, 50);
        });
    })();
</script>

<script>
    // Prevent carousel initialization on hero container
    (function() {
        if (typeof $ !== 'undefined' && typeof $.fn.owlCarousel !== 'undefined') {
            // Store original owlCarousel function
            var originalOwlCarousel = $.fn.owlCarousel;

            // Override owlCarousel to skip hero container
            $.fn.owlCarousel = function(options) {
                var $this = $(this);
                // Skip carousel initialization for hero container
                if ($this.hasClass('hero__container') || $this.hasClass('hero__slider')) {
                    return $this;
                }
                return originalOwlCarousel.apply(this, arguments);
            };
        }
    })();

    // Function to set background images
    function setBackgroundImages() {
        if (typeof $ !== 'undefined') {
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
        } else {
            // Fallback if jQuery is not loaded yet
            document.querySelectorAll('.set-bg').forEach(function(el) {
                var bg = el.getAttribute('data-setbg');
                if (bg) {
                    var existingStyle = el.getAttribute('style') || '';
                    existingStyle = existingStyle.replace(/background-image\s*:\s*[^;]+;?/gi, '').trim();
                    var newStyle = existingStyle + (existingStyle ? ' ' : '') + 'background-image: url(' + bg +
                        ');';
                    el.setAttribute('style', newStyle);
                }
            });
        }
    }

    // Function to ensure video is visible
    function ensureVideoVisible() {
        var heroContainer = document.querySelector('.hero__container');
        var video = heroContainer ? heroContainer.querySelector('video') : null;

        if (video) {
            // Ensure video has preload attribute
            if (!video.hasAttribute('preload')) {
                video.setAttribute('preload', 'auto');
            }

            // Ensure video is always visible with explicit styles
            video.style.display = 'block';
            video.style.visibility = 'visible';
            video.style.opacity = '1';
            video.style.position = 'absolute';
            video.style.top = '0';
            video.style.left = '0';
            video.style.width = '100%';
            video.style.height = '100%';
            video.style.objectFit = 'cover';
            video.style.zIndex = '0';

            // Ensure parent container has proper positioning
            if (heroContainer) {
                heroContainer.style.position = 'relative';
                heroContainer.style.overflow = 'hidden';
                // Only set height via JavaScript on desktop (>= 768px) to respect CSS media queries
                // CSS handles mobile/tablet heights via media queries
                if (!heroContainer.style.height && window.innerWidth >= 768) {
                    heroContainer.style.height = '800px';
                }
            }

            // Ensure video source exists
            var videoSrc =
                '{{ asset('
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                videos / Brader - Skate.mp4 ') }}';
            var source = video.querySelector('source');
            if (!source || !source.src || source.src.indexOf('Brader-Skate.mp4') === -1) {
                // Remove existing sources
                while (video.firstChild) {
                    video.removeChild(video.firstChild);
                }
                // Add new source
                source = document.createElement('source');
                source.src = videoSrc;
                source.type = 'video/mp4';
                video.appendChild(source);
            }

            // Ensure video is loaded
            if (video.readyState < 2) {
                if (video.networkState === 0 || video.networkState === 1) {
                    video.load();
                }
            }

            // Try to play if paused and ready
            if (video.paused && video.readyState >= 2) {
                var playPromise = video.play();
                if (playPromise !== undefined) {
                    playPromise.catch(function(err) {
                        console.log('Video play prevented:', err);
                    });
                }
            }
        }
    }

    // Set background images on DOMContentLoaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setBackgroundImages();
            ensureVideoVisible();
        });
    } else {
        setBackgroundImages();
        ensureVideoVisible();
    }

    // Set background images on Livewire init
    document.addEventListener('livewire:init', () => {
        setBackgroundImages();
        ensureVideoVisible();
    });

    // Re-set background images after Livewire updates
    document.addEventListener('livewire:update', () => {
        setTimeout(function() {
            setBackgroundImages();
            ensureVideoVisible();
        }, 50);
    });

    // Simplified video check function
    function ensureVideoAlwaysPresent() {
        var heroContainer = document.querySelector('.hero__container');
        var video = heroContainer ? heroContainer.querySelector('video') : null;

        if (video) {
            // Just ensure it's visible and playing if ready
            ensureVideoVisible();

            if (video.readyState >= 2 && video.paused) {
                video.play().catch(function() {});
            }
        }
    }

    // Handle full page reloads
    window.addEventListener('load', function() {
        setTimeout(ensureVideoAlwaysPresent, 100);
        setTimeout(ensureVideoAlwaysPresent, 500);
    });

    // Handle DOM ready (for initial page load)
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(ensureVideoAlwaysPresent, 50);
            setTimeout(ensureVideoAlwaysPresent, 200);
            setTimeout(ensureVideoAlwaysPresent, 500);
        });
    } else {
        setTimeout(ensureVideoAlwaysPresent, 50);
        setTimeout(ensureVideoAlwaysPresent, 200);
        setTimeout(ensureVideoAlwaysPresent, 500);
    }

    // Re-set background images after Livewire navigation (login, logout, etc.)
    document.addEventListener('livewire:navigated', () => {
        // Multiple checks with increasing delays to catch video removal
        setTimeout(function() {
            ensureVideoAlwaysPresent();
            setBackgroundImages();
        }, 50);

        setTimeout(function() {
            ensureVideoAlwaysPresent();
        }, 150);

        setTimeout(function() {
            ensureVideoAlwaysPresent();
        }, 300);

        setTimeout(function() {
            ensureVideoAlwaysPresent();
        }, 600);
    });

    // Watch for DOM changes (wire:ignore should prevent removal, but this is a safety net)
    if (typeof MutationObserver !== 'undefined') {
        var videoObserver = null;

        function startObserving() {
            // Disconnect existing observer if any
            if (videoObserver) {
                videoObserver.disconnect();
            }

            var heroContainer = document.querySelector('.hero__container');
            if (heroContainer) {
                videoObserver = new MutationObserver(function(mutations) {
                    var video = heroContainer.querySelector('video');
                    if (!video) {
                        // Video was removed, trigger a check
                        ensureVideoAlwaysPresent();
                    } else {
                        // Video exists, ensure it's visible and playing
                        ensureVideoVisible();
                        if (video.readyState >= 2 && video.paused) {
                            video.play().catch(function() {});
                        }
                    }
                });

                videoObserver.observe(heroContainer, {
                    childList: true,
                    subtree: true,
                    attributes: true,
                    attributeFilter: ['style', 'class']
                });
            }
        }

        // Start observing when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(startObserving, 100);
            });
        } else {
            setTimeout(startObserving, 100);
        }

        // Re-observe after Livewire navigation
        document.addEventListener('livewire:navigated', function() {
            setTimeout(function() {
                startObserving();
                ensureVideoAlwaysPresent();
            }, 100);
        });
    }

    // Periodic check to ensure video is present and playing
    setInterval(function() {
        var heroContainer = document.querySelector('.hero__container');
        var video = heroContainer ? heroContainer.querySelector('video') : null;
        if (video) {
            // Ensure video is visible
            ensureVideoVisible();

            // Try to play if paused and ready
            if (video.readyState >= 2 && video.paused) {
                video.play().catch(function() {});
            }
        }
    }, 2000); // Check every 2 seconds
</script>

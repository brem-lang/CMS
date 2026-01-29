<div>
    <!-- Hero Section Begin -->
    <section class="hero">
        <div class="hero__slider" style="position: relative;">
            <div class="hero__items" style="position: relative; overflow: hidden; height: 800px;">
                <video autoplay muted loop playsinline
                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0; display: block; visibility: visible; opacity: 1;"
                    id="hero-video" data-protected="true">
                    <source src="{{ asset('videos/Brader-Skate.mp4') }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <script>
                    // Immediately protect video from removal (runs before any other scripts)
                    (function() {
                        var video = document.getElementById('hero-video');
                        if (video) {
                            // Prevent removal by overriding removeChild
                            var parent = video.parentNode;
                            if (parent && parent.removeChild) {
                                var originalRemoveChild = parent.removeChild;
                                parent.removeChild = function(node) {
                                    if (node && node.id === 'hero-video') {
                                        console.log('Prevented video removal');
                                        return node;
                                    }
                                    return originalRemoveChild.call(this, node);
                                };
                            }

                            // Ensure video stays visible
                            video.style.display = 'block';
                            video.style.visibility = 'visible';
                            video.style.opacity = '1';

                            // Try to play
                            if (video.paused) {
                                video.play().catch(function() {});
                            }
                        }
                    })();
                </script>
                <div class="container" style="position: relative; z-index: 1;">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-8">
                            <div class="hero__text" style="position: relative; z-index: 2;">
                                <h2
                                    style="color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); opacity: 1 !important; top: 0 !important; position: relative !important;">
                                    I make people laugh, think, and chase freedom — through content that entertains,
                                    empowers, and sells.</h2>
                                <a href="/shop"
                                    class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all"
                                    style="opacity: 1 !important; top: 0 !important; position: relative !important;"
                                    onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                                    onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">

                                    Shop now
                                    <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                                </a>
                                <div class="hero__social">
                                    <a href="#"><i class="fa fa-youtube"></i></a>
                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                    <a href="#"><i class="fa fa-instagram"></i></a>
                                    <a href="#"><i class="fa fa-twitter"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- BUenas Section Begin -->
    <section class="services spad" style="margin-top:-50px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Buenas</h2>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="services__image">
                        <img src="{{ asset('img/buenas_logo.png') }}" alt="Services" class="img-fluid"
                            style="margin-top:-57px;">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="services__content">
                        <h5 class="text-secondary" style="text-align: justify;">
                            <strong>Game ni brader.</strong> laro na at ikaw na sunod pumaldo! 💸💸♥️
                            <strong>Buenas PH</strong> |
                            <strong>#PagBinuBuenasKaNgaNaman</strong> – Sign Up &amp; Win!
                        </h5>

                    </div>
                    <div class="text-center mt-3">
                        <a href="https://bit.ly/CristBriand-buenasph" target="_blank" rel="noopener noreferrer"
                            class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all"
                            onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                            onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">
                            Play Now
                            <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- BUenas Section End -->

    <!-- Product Section Begin -->
    <section class="product spad" style="margin-top: -60px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Our Merch</h2>
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
            <div class="row product__filter">
                @foreach ($products as $product)
                    <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix new-arrivals"
                        wire:key="product-{{ $product->id }}">
                        <div class="product__item">
                            <div class="product__item__pic set-bg rounded shadow-sm border-0 d-flex align-items-center justify-content-center"
                                data-setbg="{{ $product->image_url }}"
                                style="background-image: url('{{ $product->image_url }}'); position: relative; {{ ($product->stock_quantity ?? 0) == 0 ? 'opacity: 0.5;' : '' }}"
                                wire:click="selectProduct({{ $product->id }})"
                                onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.classList.add('border', 'border-primary')"
                                onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.classList.remove('border', 'border-primary')">

                                @if (($product->stock_quantity ?? 0) == 0)
                                    <div
                                        style="position: absolute; top: 10px; right: 10px; background-color: rgba(220, 53, 69, 0.95); color: white; padding: 8px 15px; border-radius: 5px; font-weight: bold; font-size: 12px; text-transform: uppercase; z-index: 10; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
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
                @endforeach
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <a href="/shop" class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all"
                        onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                        onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">
                        View All Products
                        <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->

    <!-- Services Section Begin -->
    <section class="services spad" style="margin-top:-80px;">
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
    </section>
    <!-- Services Section End -->

    <!-- Latest Blog Section Begin -->
    <section class="latest spad" style="margin-top:-120px;">
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
            <div class="row">
                @foreach ($blogs as $blog)
                    <div class="col-lg-4 col-md-6 col-sm-6" wire:key="blog-{{ $blog->id }}">
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
        </div>
    </section>
    <!-- Latest Blog Section End -->
</div>

<script>
    // Immediately prevent carousel initialization - must run before main.js
    (function() {
        // Check if hero slider has video and prevent carousel
        function preventCarouselInit() {
            var heroSlider = document.querySelector('.hero__slider');
            if (heroSlider && heroSlider.querySelector('video')) {
                // Remove owl-carousel class if it exists
                heroSlider.classList.remove('owl-carousel');

                // Protect video from removal - add a data attribute
                var video = heroSlider.querySelector('video#hero-video');
                if (video) {
                    video.setAttribute('data-protected', 'true');
                }
            }
        }

        // Run immediately
        preventCarouselInit();

        // Also run when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', preventCarouselInit);
        } else {
            preventCarouselInit();
        }

        // Also run on window load (for full page reloads like logout)
        window.addEventListener('load', function() {
            setTimeout(preventCarouselInit, 50);
            setTimeout(preventCarouselInit, 200);
        });
    })();
</script>

<script>
    // Prevent global Owl Carousel initialization on hero slider with video
    (function() {
        if (typeof $ !== 'undefined' && typeof $.fn.owlCarousel !== 'undefined') {
            // Store original owlCarousel function
            var originalOwlCarousel = $.fn.owlCarousel;

            // Override owlCarousel to skip hero slider if it has a video
            $.fn.owlCarousel = function(options) {
                var $this = $(this);
                // If this is the hero slider and it contains a video, skip initialization
                if ($this.hasClass('hero__slider') && $this.find('video').length > 0) {
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

    // Function to ensure video is visible and destroy any carousel instances
    function ensureVideoVisible() {
        var video = document.getElementById('hero-video') || document.querySelector('.hero__slider video');
        var $slider = typeof $ !== 'undefined' ? $('.hero__slider') : null;

        if (video) {
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
            var heroItems = video.closest('.hero__items');
            if (heroItems) {
                heroItems.style.position = 'relative';
                heroItems.style.overflow = 'hidden';
                if (!heroItems.style.height) {
                    heroItems.style.height = '800px';
                }
            }

            // Destroy any existing Owl Carousel instance
            if ($slider && $slider.length && typeof $.fn.owlCarousel !== 'undefined') {
                if ($slider.data('owl.carousel')) {
                    try {
                        $slider.trigger('destroy.owl.carousel');
                        $slider.removeData('owl.carousel');
                    } catch (e) {
                        // Carousel already destroyed or not initialized
                    }
                }
                // Remove owl-carousel class if it exists
                $slider.removeClass('owl-carousel');
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

    // Function to recreate video element if it was removed during navigation
    function recreateVideoIfNeeded() {
        var heroSlider = document.querySelector('.hero__slider');
        if (!heroSlider) return;

        var heroItems = heroSlider.querySelector('.hero__items');
        if (!heroItems) return;

        var video = heroItems.querySelector('video#hero-video');

        // If video doesn't exist, recreate it
        if (!video) {
            video = document.createElement('video');
            video.id = 'hero-video';
            video.setAttribute('autoplay', '');
            video.setAttribute('muted', '');
            video.setAttribute('loop', '');
            video.setAttribute('playsinline', '');
            video.setAttribute('data-protected', 'true');
            video.style.cssText =
                'position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0; display: block; visibility: visible; opacity: 1;';

            var source = document.createElement('source');
            source.src = '{{ asset('videos/Brader-Skate.mp4') }}';
            source.type = 'video/mp4';
            video.appendChild(source);

            // Insert video as first child of hero__items
            heroItems.insertBefore(video, heroItems.firstChild);

            // Ensure video plays
            video.play().catch(function(err) {
                console.log('Video autoplay prevented:', err);
            });
        } else {
            // Video exists, ensure it's protected and visible
            video.setAttribute('data-protected', 'true');
            ensureVideoVisible();
            if (video.paused) {
                video.play().catch(function(err) {
                    console.log('Video play prevented:', err);
                });
            }
        }
    }

    // Function to ensure video is always present (called on all page events)
    function ensureVideoAlwaysPresent() {
        recreateVideoIfNeeded();
        ensureVideoVisible();
    }

    // Handle full page reloads (logout uses form submission, not Livewire)
    window.addEventListener('load', function() {
        setTimeout(ensureVideoAlwaysPresent, 100);
        setTimeout(ensureVideoAlwaysPresent, 300);
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

    // Watch for DOM changes that might remove the video (login, logout, navigation)
    if (typeof MutationObserver !== 'undefined') {
        var videoObserver = null;

        function startObserving() {
            // Disconnect existing observer if any
            if (videoObserver) {
                videoObserver.disconnect();
            }

            var heroSlider = document.querySelector('.hero__slider');
            if (heroSlider) {
                videoObserver = new MutationObserver(function(mutations) {
                    var video = heroSlider.querySelector('video#hero-video');
                    if (!video) {
                        // Video was removed, recreate it immediately
                        recreateVideoIfNeeded();
                    } else {
                        // Video exists, ensure it's visible
                        ensureVideoVisible();
                    }
                });

                videoObserver.observe(heroSlider, {
                    childList: true,
                    subtree: true,
                    attributes: true,
                    attributeFilter: ['style', 'class']
                });
            }
        }

        // Start observing immediately
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(startObserving, 50);
            });
        } else {
            setTimeout(startObserving, 50);
        }

        // Re-observe after Livewire navigation (login, logout, etc.)
        document.addEventListener('livewire:navigated', function() {
            setTimeout(function() {
                startObserving();
                recreateVideoIfNeeded();
            }, 50);
        });

        // Also re-observe after Livewire updates
        document.addEventListener('livewire:update', function() {
            setTimeout(function() {
                var heroSlider = document.querySelector('.hero__slider');
                if (!heroSlider || !heroSlider.querySelector('video#hero-video')) {
                    startObserving();
                    recreateVideoIfNeeded();
                }
            }, 50);
        });
    }

    // Additional check: periodically verify video exists (as fallback)
    // This ensures video persists even if something removes it
    setInterval(function() {
        var heroSlider = document.querySelector('.hero__slider');
        if (heroSlider) {
            var video = heroSlider.querySelector('video#hero-video');
            if (!video) {
                recreateVideoIfNeeded();
                ensureVideoVisible();
            } else {
                // Video exists, but ensure it's visible and playing
                ensureVideoVisible();
                if (video.paused) {
                    video.play().catch(function(err) {
                        console.log('Video play prevented:', err);
                    });
                }
            }
        }
    }, 500); // Check every 500ms for faster recovery

    // Also intercept any attempts to remove the video element (for full page reloads like logout)
    if (typeof MutationObserver !== 'undefined') {
        var removalObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                mutation.removedNodes.forEach(function(node) {
                    if (node.nodeType === 1 && node.id === 'hero-video') {
                        // Video was removed, recreate immediately
                        setTimeout(function() {
                            recreateVideoIfNeeded();
                            ensureVideoVisible();
                        }, 10);
                    }
                });
            });
        });

        // Observe the entire document for video removal
        function startRemovalObserver() {
            if (document.body) {
                removalObserver.observe(document.body, {
                    childList: true,
                    subtree: true
                });
            }
        }

        if (document.body) {
            startRemovalObserver();
        } else {
            document.addEventListener('DOMContentLoaded', startRemovalObserver);
        }

        // Re-observe after page load (for full page reloads)
        window.addEventListener('load', function() {
            setTimeout(startRemovalObserver, 100);
        });
    }
</script>

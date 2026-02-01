<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? config('app.name') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/brader_favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('img/brader_favicon.jpg') }}" type="image/jpeg">
    <link rel="apple-touch-icon" href="{{ asset('img/brader_favicon.jpg') }}">

    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Bootstrap Template CSS Files -->
    <link rel="stylesheet" href="{{ asset('bootstrap/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/magnific-popup.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/nice-select.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/style.css') }}" type="text/css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div id="preloder">
        <div class="loader"></div>
    </div>

    @if (!request()->routeIs('login') && !request()->routeIs('register'))
        <header class="mb-4">
            @include('components.layout.header')
        </header>
    @endif

    <!-- Notification Toast -->
    <div id="cart-notification" class="alert alert-success alert-dismissible fade" role="alert"
        style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px; display: none;">
        <i class="fa fa-check-circle"></i> <span id="notification-message"></span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <main>
        @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert"
                style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <i class="fa fa-check-circle"></i> {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        {{ $slot }}
    </main>

    @livewire('cart-sidebar')

    <!-- Data attributes for JavaScript (Blade values passed to JS) -->
    <div data-video-path="{{ asset('videos/Brader-Skate.mp4') }}" style="display: none;"></div>
    @if (session('message'))
        <div data-session-message="1" style="display: none;"></div>
    @endif

    <!-- Bootstrap Template JavaScript Files -->
    <!-- jQuery (must load first) -->
    <script src="{{ asset('bootstrap/js/jquery-3.3.1.min.js') }}"></script>

    <!-- Bootstrap JS -->
    <script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>

    <!-- Plugins -->
    <script src="{{ asset('bootstrap/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('bootstrap/js/mixitup.min.js') }}"></script>
    <script src="{{ asset('bootstrap/js/owl.carousel.min.js') }}"></script>

    <!-- Main Template JS (must load last) -->
    <script src="{{ asset('bootstrap/js/main.js') }}"></script>

    <!-- Prevent Owl Carousel from initializing on hero slider with video -->
    <script>
        // Override owlCarousel BEFORE main.js loads to prevent hero slider initialization
        (function() {
            // Wait for jQuery to be available
            function setupOwlCarouselOverride() {
                if (typeof $ !== 'undefined' && typeof $.fn !== 'undefined') {
                    // Only override if not already overridden
                    if (!$.fn.owlCarousel._original) {
                        // Store original owlCarousel
                        $.fn.owlCarousel._original = $.fn.owlCarousel;

                        // Override owlCarousel
                        $.fn.owlCarousel = function(options) {
                            // Check if this selector matches hero slider
                            var isHeroSlider = false;
                            var hasVideo = false;

                            // Check each element in the jQuery collection
                            this.each(function() {
                                var $el = $(this);
                                if ($el.hasClass('hero__slider') || $el.is('.hero__slider')) {
                                    isHeroSlider = true;
                                    if ($el.find('video').length > 0) {
                                        hasVideo = true;
                                        return false; // break loop
                                    }
                                }
                            });

                            // If hero slider with video, skip initialization
                            if (isHeroSlider && hasVideo) {
                                console.log('Skipping Owl Carousel initialization on hero slider with video');
                                // Ensure video stays visible
                                var $video = this.find('video#hero-video');
                                if ($video.length) {
                                    $video.css({
                                        'display': 'block',
                                        'visibility': 'visible',
                                        'opacity': '1'
                                    });
                                    var videoEl = $video[0];
                                    if (videoEl && videoEl.paused) {
                                        videoEl.play().catch(function() {});
                                    }
                                }
                                return this; // Return jQuery object without initializing
                            }

                            // For all other cases, use original owlCarousel
                            return $.fn.owlCarousel._original.apply(this, arguments);
                        };
                    }
                }
            }

            // Try immediately
            setupOwlCarouselOverride();

            // Also try when DOM is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', setupOwlCarouselOverride);
            }
        })();
    </script>

    <!-- Immediately after main.js loads, restore video if it was removed -->
    <script>
        (function() {
            function restoreVideoAndPreventCarousel() {
                if (typeof $ !== 'undefined') {
                    var $heroSlider = $('.hero__slider');
                    if ($heroSlider.length) {
                        var $heroItems = $heroSlider.find('.hero__items');
                        if ($heroItems.length) {
                            var $video = $heroItems.find('video#hero-video');
                            var hasVideo = $video.length > 0;

                            // If video doesn't exist, recreate it
                            if (!hasVideo) {
                                console.log('Video was removed, recreating...');
                                var videoHtml =
                                    '<video autoplay muted loop playsinline id="hero-video" data-protected="true" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0; display: block; visibility: visible; opacity: 1;"><source src="{{ asset('videos/Brader-Skate.mp4') }}" type="video/mp4">Your browser does not support the video tag.</video>';
                                $heroItems.prepend(videoHtml);
                                $video = $heroItems.find('video#hero-video');

                                // Try to play
                                var videoEl = $video[0];
                                if (videoEl) {
                                    videoEl.play().catch(function() {});
                                }
                            }

                            // Destroy any carousel instance
                            if ($heroSlider.hasClass('owl-carousel')) {
                                try {
                                    $heroSlider.trigger('destroy.owl.carousel');
                                } catch (e) {}
                                $heroSlider.removeClass('owl-carousel');
                            }

                            // Ensure video is visible and styled correctly
                            if ($video.length) {
                                $video.css({
                                    'display': 'block',
                                    'visibility': 'visible',
                                    'opacity': '1',
                                    'position': 'absolute',
                                    'top': '0',
                                    'left': '0',
                                    'width': '100%',
                                    'height': '100%',
                                    'object-fit': 'cover',
                                    'z-index': '0'
                                });

                                // Ensure parent has correct styles
                                $heroItems.css({
                                    'position': 'relative',
                                    'overflow': 'hidden',
                                    'height': '800px'
                                });

                                // Try to play video
                                var videoEl = $video[0];
                                if (videoEl && videoEl.paused) {
                                    videoEl.play().catch(function() {});
                                }
                            }
                        }
                    }
                }
            }

            // Run immediately after main.js (synchronous)
            restoreVideoAndPreventCarousel();

            // Also run with delays to catch any async initialization
            setTimeout(restoreVideoAndPreventCarousel, 10);
            setTimeout(restoreVideoAndPreventCarousel, 50);
            setTimeout(restoreVideoAndPreventCarousel, 100);
            setTimeout(restoreVideoAndPreventCarousel, 300);
            setTimeout(restoreVideoAndPreventCarousel, 500);

            // Run on DOM ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    setTimeout(restoreVideoAndPreventCarousel, 50);
                    setTimeout(restoreVideoAndPreventCarousel, 200);
                });
            }

            // Run on window load (for full page reloads like logout)
            window.addEventListener('load', function() {
                setTimeout(restoreVideoAndPreventCarousel, 50);
                setTimeout(restoreVideoAndPreventCarousel, 200);
                setTimeout(restoreVideoAndPreventCarousel, 500);
                setTimeout(restoreVideoAndPreventCarousel, 1000);
            });

            // Periodic check as fallback
            setInterval(function() {
                var $heroSlider = $('.hero__slider');
                if ($heroSlider.length) {
                    var $video = $heroSlider.find('video#hero-video');
                    if (!$video.length) {
                        restoreVideoAndPreventCarousel();
                    }
                }
            }, 500);
        })();
    </script>

    <!-- Cart Notification Script -->
    <script>
        (function() {
            let notificationTimeout = null;

            function showCartNotification(messageText) {
                const notification = document.getElementById('cart-notification');
                const message = document.getElementById('notification-message');

                if (!notification || !message) return;

                // Clear any existing timeout to prevent conflicts
                if (notificationTimeout) {
                    clearTimeout(notificationTimeout);
                    notificationTimeout = null;
                }

                // Reset notification state completely
                notification.classList.remove('show');
                notification.style.display = 'none';
                
                // Small delay to ensure CSS transition resets
                setTimeout(() => {
                    // Set message
                    message.textContent = messageText || 'Product added to cart!';
                    
                    // Show notification
                    notification.style.display = 'block';
                    
                    // Force reflow
                    void notification.offsetHeight;
                    
                    // Add show class for animation
                    notification.classList.add('show');

                    // Auto-hide after 3 seconds
                    notificationTimeout = setTimeout(() => {
                        notification.classList.remove('show');
                        setTimeout(() => {
                            notification.style.display = 'none';
                            notificationTimeout = null;
                        }, 150);
                    }, 3000);
                }, 10);
            }

            // Event handler function
            function handleCartUpdated(...args) {
                // Livewire v3 can pass events in different formats
                let messageText = 'Product added to cart!';
                
                // Check all arguments passed to the handler
                for (let arg of args) {
                    // Format 1: Array with data object [eventName, {message: '...'}]
                    if (Array.isArray(arg) && arg.length > 1) {
                        const data = arg[1];
                        if (data && typeof data === 'object' && data.message) {
                            messageText = data.message;
                            break;
                        }
                    }
                    // Format 2: Direct object with message property
                    else if (arg && typeof arg === 'object') {
                        if (arg.message) {
                            messageText = arg.message;
                            break;
                        }
                        if (arg.detail && arg.detail.message) {
                            messageText = arg.detail.message;
                            break;
                        }
                    }
                    // Format 3: Direct string message
                    else if (typeof arg === 'string') {
                        messageText = arg;
                        break;
                    }
                }
                
                showCartNotification(messageText);
            }

            // Setup listener when Livewire initializes
            document.addEventListener('livewire:init', () => {
                if (window.Livewire) {
                    Livewire.on('cartUpdated', handleCartUpdated);
                }
            });

            // Re-setup listener after SPA navigation (Livewire v3 may clear listeners)
            document.addEventListener('livewire:navigated', () => {
                if (window.Livewire) {
                    Livewire.on('cartUpdated', handleCartUpdated);
                }
            });

            // Also setup immediately if Livewire is already loaded
            if (window.Livewire) {
                Livewire.on('cartUpdated', handleCartUpdated);
            } else if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => {
                    if (window.Livewire) {
                        Livewire.on('cartUpdated', handleCartUpdated);
                    }
                });
            }

            // Fallback: Also listen using window event
            window.addEventListener('cartUpdated', (event) => {
                const message = event.detail?.message || event.detail || 'Product added to cart!';
                showCartNotification(message);
            });
        })();

        // Hide preloader on Livewire navigation
        document.addEventListener('livewire:navigated', () => {
            if (typeof $ !== 'undefined') {
                $(".loader").fadeOut();
                $("#preloder").delay(200).fadeOut("slow");
            } else {
                // Fallback if jQuery isn't loaded yet
                const loader = document.querySelector(".loader");
                const preloder = document.getElementById("preloder");
                if (loader) loader.style.display = 'none';
                if (preloder) preloder.style.display = 'none';
            }
        });

        // Handle session flash messages
        @if (session('message'))
            setTimeout(() => {
                $('.alert').fadeOut('slow');
            }, 3000);
        @endif
    </script>

    @if (!request()->routeIs('login') && !request()->routeIs('register'))
        {{-- footer --}}
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="footer__about">
                            <div class="footer__logo">
                                <a href="#"><img src="{{ asset('img/footer.png') }}" alt=""
                                        style="height: 70px;"></a>
                            </div>
                            <p>BLESS AND LOVE</p>
                            <div class="hero__social" style="margin-top: -10px;">
                                <a href="https://www.youtube.com/@cristbriand3086" target="_blank"><i
                                        class="fa fa-youtube"></i></a>
                                <a href="https://www.facebook.com/cristbriand.brader" target="_blank"><i
                                        class="fa fa-facebook"></i></a>
                                <a href="http://instagram.com/crist.briand" target="_blank"><i
                                        class="fa fa-instagram"></i></a>
                                <a href="https://www.tiktok.com/@crist.briand" target="_blank"><i
                                        class="fa fa-video-camera"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-2 offset-lg-1 col-md-3 col-sm-6">
                        <div class="footer__widget">
                            <h6>Quick Links</h6>
                            <ul>
                                <li><a href="{{ route('home') }}">Home</a></li>
                                <li><a href="{{ route('shop') }}">Our Merchandise</a></li>
                                <li><a href="{{ route('blog') }}">Blog</a></li>
                                <li><a href="{{ route('about') }}">About</a></li>
                                <li><a href="{{ route('contact') }}">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-3 col-sm-6">
                        <div class="footer__widget">
                            <h6>Customer Service</h6>
                            <ul>
                                <li><a href="{{ route('track-order') }}">Track Order</a></li>
                                <li><a href="{{ route('return-and-refund') }}">Refund and Returns Policy</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 offset-lg-1 col-md-6 col-sm-6">
                        <div class="footer__widget">
                            <h6>Stay Updated</h6>
                            <div class="footer__newslatter">
                                <p>Subscribe to get updates on new arrivals and exclusive offers.</p>
                                {{-- <form action="#">
                                    <input type="text" placeholder="Your email">
                                    <button type="submit"><span class="icon_mail_alt"></span></button>
                                </form> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="footer__copyright__text">
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            <p>Copyright ©
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                                All rights reserved | This template is made with <i class="fa fa-heart-o"
                                    aria-hidden="true"></i> by <a href="https://colorlib.com"
                                    target="_blank">Colorlib</a>
                            </p>
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    @endif
</body>

</html>

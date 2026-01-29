import './bootstrap';

// Note: Legacy JavaScript files (jQuery and plugins) are loaded via script tags
// in the layout file because they are not ES modules. Vite will handle
// the custom scripts below, but vendor scripts remain as script tags for compatibility.

// Wait for DOM and jQuery to be ready
let restoreVideoAndPreventCarousel;

function initCustomScripts() {
    // Prevent Owl Carousel from initializing on hero slider with video
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

    // Setup Owl Carousel override
    setupOwlCarouselOverride();

    // Restore video and prevent carousel initialization
    restoreVideoAndPreventCarousel = function() {
        if (typeof $ !== 'undefined') {
            var $heroSlider = $('.hero__slider');
            if ($heroSlider.length) {
                var $heroItems = $heroSlider.find('.hero__items');
                if ($heroItems.length) {
                    var $video = $heroItems.find('video#hero-video');
                    var hasVideo = $video.length > 0;
                    const videoPath = document.querySelector('[data-video-path]')?.dataset.videoPath || '/videos/Brader-Skate.mp4';

                    // If video doesn't exist, recreate it
                    if (!hasVideo) {
                        console.log('Video was removed, recreating...');
                        var videoHtml =
                            `<video autoplay muted loop playsinline id="hero-video" data-protected="true" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 0; display: block; visibility: visible; opacity: 1;"><source src="${videoPath}" type="video/mp4">Your browser does not support the video tag.</video>`;
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
    };

    // Run video restoration with delays
    restoreVideoAndPreventCarousel();
    setTimeout(restoreVideoAndPreventCarousel, 10);
    setTimeout(restoreVideoAndPreventCarousel, 50);
    setTimeout(restoreVideoAndPreventCarousel, 100);
    setTimeout(restoreVideoAndPreventCarousel, 300);
    setTimeout(restoreVideoAndPreventCarousel, 500);

    // Cart Notification Script
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
    function handleCartUpdated(event) {
        // In Livewire v3, event is an array: [eventName, dataObject]
        let messageText = 'Product added to cart!';
        
        if (Array.isArray(event) && event.length > 1) {
            const data = event[1];
            if (data && typeof data === 'object' && data.message) {
                messageText = data.message;
            }
        } else if (event && typeof event === 'object' && event.message) {
            // Fallback for different event structures
            messageText = event.message;
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
    const sessionMessage = document.querySelector('[data-session-message]');
    if (sessionMessage) {
        setTimeout(() => {
            if (typeof $ !== 'undefined') {
                $('.alert').fadeOut('slow');
            } else {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    alert.style.display = 'none';
                });
            }
        }, 3000);
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCustomScripts);
} else {
    // DOM already loaded, but wait for jQuery
    if (typeof $ !== 'undefined') {
        initCustomScripts();
    } else {
        // Wait for jQuery to load
        const checkJQuery = setInterval(() => {
            if (typeof $ !== 'undefined') {
                clearInterval(checkJQuery);
                initCustomScripts();
            }
        }, 50);
    }
}

// Also run on window load
window.addEventListener('load', () => {
    setTimeout(initCustomScripts, 50);
    setTimeout(initCustomScripts, 200);
    setTimeout(initCustomScripts, 500);
    setTimeout(initCustomScripts, 1000);
});

// Periodic check for video restoration
setInterval(function() {
    if (typeof $ !== 'undefined') {
        var $heroSlider = $('.hero__slider');
        if ($heroSlider.length) {
            var $video = $heroSlider.find('video#hero-video');
            if (!$video.length) {
                if (typeof restoreVideoAndPreventCarousel !== 'undefined') {
                    restoreVideoAndPreventCarousel();
                }
            }
        }
    }
}, 500);

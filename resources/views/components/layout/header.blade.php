    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-7">
                        <div class="header__top__left">
                            <p>Free shipping, 30-day return or refund guarantee.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-5">
                        <div class="header__top__right">
                            <div class="header__top__links">
                                @auth
                                    <a href="#"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign
                                        out</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                @else
                                    <a href="{{ route('login') }}">Sign in</a>
                                @endauth
                                {{-- <a href="#">FAQs</a> --}}
                            </div>
                            {{-- <div class="header__top__hover">
                                <span>Usd <i class="arrow_carrot-down"></i></span>
                                <ul>
                                    <li>USD</li>
                                    <li>EUR</li>
                                    <li>USD</li>
                                </ul>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-lg-3 col-md-3">
                    <div class="header__logo">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('img/logo.webp') }}" alt="" class="mt-n4 mb-n5">
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <nav class="header__menu mobile-menu">
                        <ul>
                            <li class="{{ request()->is('/') ? 'active' : '' }}"><a href="/">Home</a></li>
                            <li class="{{ request()->is('shop') ? 'active' : '' }}"><a href="/shop">Our
                                    Merchandise</a></li>
                            <li class="{{ request()->is('about') ? 'active' : '' }}"><a href="/about">About</a></li>
                            <li class="{{ request()->is('blog') ? 'active' : '' }}"><a href="/blog">Blog</a></li>
                            <li class="{{ request()->is('contact') ? 'active' : '' }}"><a href="/contact">Contact</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="col-lg-3 col-md-3">
                    <nav class="header__menu mobile-menu">
                        <ul class="d-flex align-items-center justify-content-end mb-0" style="list-style: none;">
                            {{-- @auth
                                <li class="me-3">
                                    <a href="#" class="text-dark text-decoration-none fw-bold"
                                        style="text-decoration: none;">
                                        {{ auth()->user()->name }}
                                    </a>
                                </li>
                            @endauth --}}
                            @auth
                                <li class="position-relative me-3">
                                    <a href="{{ route('orders') }}" class="text-decoration-none"
                                        style="text-decoration: none; cursor: pointer;">
                                        <i class="fa fa-book" style="font-size: 30px; color: #111111;"></i>
                                    </a>
                                </li>
                            @endauth
                            <li class="position-relative">
                                <a href="#" class="text-decoration-none cart-icon-click"
                                    style="text-decoration: none; cursor: pointer;">
                                    <i class="fa fa-cart-arrow-down" style="font-size: 30px; color: #111111;"></i>
                                    @livewire('cart-count')
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="canvas__open"><i class="fa fa-bars"></i></div>
        </div>
    </header>

    <script>
        function setupCartClick() {
            document.querySelectorAll('.cart-icon-click').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (window.Livewire) {
                        // Dispatch to all Livewire components
                        Livewire.dispatch('openCartSidebar');
                    }
                });
            });
        }

        // Setup on initial page load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupCartClick);
        } else {
            setupCartClick();
        }

        // Reinitialize after Livewire updates
        document.addEventListener('livewire:navigated', setupCartClick);
    </script>

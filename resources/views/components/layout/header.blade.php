        <!-- Offcanvas Menu Begin -->
        <div class="offcanvas-menu-overlay"></div>
        <div class="offcanvas-menu-wrapper">
            <div class="offcanvas__option">
                <div class="offcanvas__links">
                    @auth
                        <a href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form-offcanvas').submit();">Sign
                            out</a>
                        <form id="logout-form-offcanvas" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>

                        <a href="{{ route('return-and-refund') }}">Policy</a>
                    @else
                        <a href="{{ route('login') }}">Sign in</a>
                        <a href="{{ route('return-and-refund') }}">Policy</a>
                    @endauth
                </div>
            </div>
            <div class="offcanvas__nav__option">
                @auth
                    <a href="{{ route('orders') }}">
                        <i class="fa fa-book" style="font-size: 24px; color: #111111;"></i>
                    </a>
                @endauth
                <a href="#" class="cart-icon-click">
                    <i class="fa fa-shopping-cart" style="font-size: 24px; color: #111111;"></i>
                    <div style="margin-top: -40px;position: absolute; margin-left: 18px;">
                        @livewire('cart-count')
                    </div>
                </a>
                <div class="price">@livewire('cart-total')</div>
            </div>
            <div id="mobile-menu-wrap"></div>
            <div class="offcanvas__text">
                <p>Free shipping, 30-day return or refund guarantee.</p>
            </div>
        </div>
        <!-- Offcanvas Menu End -->

        <header class="header">
            <div class="header__top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6 col-md-7 col-12">
                            <div class="header__top__left">
                                <p>Free shipping, 30-day return or refund guarantee.</p>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-5 col-12">
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
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row d-flex align-items-center">
                    <div class="col-lg-3 col-md-3 col-6">
                        <div class="header__logo">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('img/bradernewlogo.png') }}" alt="" class="mt-n4 mb-n5"
                                    style="max-width: 100%; height: auto;">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 d-none d-md-block">
                        <nav class="header__menu mobile-menu">
                            <ul>
                                <li class="{{ request()->is('/') ? 'active' : '' }}"><a href="/">Home</a></li>
                                <li class="{{ request()->is('shop') ? 'active' : '' }}"><a href="/shop">Shop</a></li>
                                <li class="{{ request()->is('digital-products') ? 'active' : '' }}"><a
                                        href="/digital-products">Digital Products</a></li>
                                {{-- <li class="{{ request()->is('about') ? 'active' : '' }}"><a href="/about">About</a>
                                </li> --}}
                                <li class="{{ request()->is('blog') ? 'active' : '' }}"><a href="/blog">Blog</a></li>
                                <li class="{{ request()->is('contact') ? 'active' : '' }}"><a
                                        href="/contact">Contact</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-lg-3 col-md-3 col-6">
                        <div class="header__nav__option">
                            @auth
                                <a href="{{ route('orders') }}">
                                    <i class="fa fa-book" style="font-size: 24px; color: #111111;"></i>
                                </a>
                            @endauth
                            <a href="#" class="cart-icon-click">
                                <i class="fa fa-shopping-cart" style="font-size: 24px; color: #111111;"></i>
                                <div style="margin-top: -40px;position: absolute; margin-left: 18px;">
                                    @livewire('cart-count')
                                </div>
                            </a>
                            <div class="price">@livewire('cart-total')</div>
                        </div>
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

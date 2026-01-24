<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? config('app.name') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
        rel="stylesheet">

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

    <!-- Cart Notification Script -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('cartUpdated', (data) => {
                const notification = document.getElementById('cart-notification');
                const message = document.getElementById('notification-message');

                if (notification && message) {
                    message.textContent = data.message || 'Product added to cart!';
                    notification.style.display = 'block';
                    notification.classList.add('show');

                    // Auto-hide after 3 seconds
                    setTimeout(() => {
                        notification.classList.remove('show');
                        setTimeout(() => {
                            notification.style.display = 'none';
                        }, 150);
                    }, 3000);
                }
            });
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
                                <li><a href="#">Shipping Info</a></li>
                                <li><a href="#">FAQ</a></li>
                                <li><a href="{{ route('return-and-refund') }}">Refund and Returns Policy</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 offset-lg-1 col-md-6 col-sm-6">
                        <div class="footer__widget">
                            <h6>Stay Updated</h6>
                            <div class="footer__newslatter">
                                <p>Subscribe to get updates on new arrivals and exclusive offers.</p>
                                <form action="#">
                                    <input type="text" placeholder="Your email">
                                    <button type="submit"><span class="icon_mail_alt"></span></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="footer__copyright__text">
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        <p>Copyright ©
                            <script>
                                document.write(new Date().getFullYear());
                            </script>2020
                            All rights reserved | This template is made with <i class="fa fa-heart-o"
                                aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                        </p>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    </div>
                </div>
            </div> --}}
            </div>
        </footer>
    @endif
</body>

</html>

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
                                    I just live my life… and turn it into content.
                                </span>
                            </h2>
                            <a href="#my-content"
                                class="primary-btn d-inline-block text-decoration-none shadow-lg transition-all text-white"
                                style="opacity: 1 !important; top: 0 !important; position: relative !important; color: white !important; font-size: 16px; padding: 16px 34px;"
                                onmouseover="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.add('ms-3')"
                                onmouseout="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.remove('ms-3')">
                                Start Here
                                <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                            </a>
                            <br>
                            <br>
                            <a href="#offers-section"
                                class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all text-white"
                                style="opacity: 1 !important; top: 0 !important; position: relative !important; color: white !important; font-size: 16px; padding: 16px 34px;"
                                onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                                onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">
                                Offers
                                <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- My Content Section Begin -->
    <section id="my-content" class="latest spad" style="margin-top:-50px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title start-here-title-wrap">
                        <span class="start-here-kicker">Watch & Enjoy</span>
                        <h2 class="start-here-title">My Content</h2>
                        <p class="start-here-subtitle">Tap a video to open fullscreen and play instantly.</p>
                    </div>
                </div>
            </div>
            <div class="row d-none d-md-flex">
                @forelse ($highlightContents->take(3) as $content)
                <div class="col-lg-4 col-md-6 mb-4" wire:key="mycontent-thumb-{{ $content->id }}">
                    <div class="blog__item">
                        <div class="blog__item__pic" style="min-height: 260px; background: #111;">
                            <video preload="metadata" muted playsinline
                                style="width: 100%; height: 100%; min-height: 260px; object-fit: cover; display: block;">
                                <source src="{{ $content->video_url }}" type="video/mp4">
                            </video>
                        </div>
                        <div class="blog__item__text">
                            <h5 style="color: #333333;">{{ $content->title ?: 'Highlighted Video' }}</h5>
                            <a href="javascript:void(0)"
                                wire:click.prevent="openHighlightModal({{ $content->id }})"
                                class="text-primary fw-bold text-decoration-none shadow-hover"
                                style="color: #007bff;">
                                Play Video
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <p class="text-secondary text-center">No highlighted videos yet.</p>
                </div>
                @endforelse
            </div>

            <div class="row d-block d-md-none">
                @if ($highlightContents->isNotEmpty())
                @php $firstContent = $highlightContents->first(); @endphp
                <div class="col-12" wire:key="mycontent-mobile-{{ $firstContent->id }}">
                    <div class="blog__item">
                        <div class="blog__item__pic" style="min-height: 300px; background: #111;">
                            <video preload="metadata" muted playsinline
                                style="width: 100%; height: 100%; min-height: 300px; object-fit: cover; display: block;">
                                <source src="{{ $firstContent->video_url }}" type="video/mp4">
                            </video>
                        </div>
                        <div class="blog__item__text">
                            <h5 style="color: #333333;">{{ $firstContent->title ?: 'Highlighted Video' }}</h5>
                            <a href="javascript:void(0)"
                                wire:click.prevent="openHighlightModal({{ $firstContent->id }})"
                                class="text-primary fw-bold text-decoration-none shadow-hover"
                                style="color: #007bff;">
                                Play Video
                            </a>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-12">
                    <p class="text-secondary text-center">No highlighted videos yet.</p>
                </div>
                @endif
            </div>
        </div>
    </section>
    <!-- My Content Section End -->

    <!-- Start Here Section Begin -->
    <section id="start-here" class="latest spad" style="margin-top:-80px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title start-here-title-wrap">
                        <span class="start-here-kicker">Featured Journey</span>
                        <h2 class="start-here-title">Start Here</h2>
                        <p class="start-here-subtitle">Read the latest story and listen to free meditations.</p>
                    </div>
                </div>
            </div>
            @if ($featuredBlog)
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow-sm border-0" style="border-radius: 10px; overflow: hidden;">
                        <div style="background: #f3f3f3;">
                            <img src="{{ $featuredBlog->image_url }}" alt="{{ $featuredBlog->title }}"
                                style="width: 100%; height: auto; max-height: 600px; object-fit: contain; display: block;">
                        </div>
                        <div class="p-4">
                            <span style="color: #666666;"><img src="{{ asset('bootstrap/img/icon/calendar.png') }}" alt="">
                                {{ $featuredBlog->created_at->format('d F Y') }}</span>
                            <h4 class="mt-2 mb-3" style="color: #333333;">{{ $featuredBlog->title }}</h4>
                            <a href="javascript:void(0)" wire:click.prevent="openBlog({{ $featuredBlog->id }})"
                                class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all"
                                onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                                onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">
                                Read Blog
                                <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4 d-none d-md-flex">
                <div class="col-lg-12">
                    <h4 class="mb-3" style="font-weight: 700;">Free Meditation</h4>
                </div>
                @forelse ($freeMeditations as $meditation)
                <div class="col-lg-4 col-md-6 mb-4" wire:key="free-meditation-{{ $meditation->id }}">
                    <div class="card shadow-sm border-0 h-100" style="border-radius: 10px;">
                        <div class="p-4 d-flex flex-column h-100">
                            <h6 class="mb-2" style="font-weight: 700;">{{ $meditation->title }}</h6>
                            <p class="text-secondary mb-4" style="font-size: 14px; line-height: 1.5;">
                                {{ \Illuminate\Support\Str::limit(strip_tags($meditation->description ?? ''), 90) }}
                            </p>
                            <div class="mt-auto">
                                <a href="javascript:void(0)"
                                    wire:click.prevent="selectDigitalProduct({{ $meditation->id }})"
                                    class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all"
                                    onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                                    onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">
                                    Listen Audio
                                    <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <p class="text-secondary">No free meditation available yet.</p>
                </div>
                @endforelse
            </div>

            <div class="row mt-4 d-block d-md-none">
                <div class="col-12">
                    <h4 class="mb-3" style="font-weight: 700;">Free Meditation</h4>
                </div>
                @if ($freeMeditations->isNotEmpty())
                @php $firstMeditation = $freeMeditations->first(); @endphp
                <div class="col-12" wire:key="free-meditation-mobile-{{ $firstMeditation->id }}">
                    <div class="card shadow-sm border-0" style="border-radius: 10px;">
                        <div class="p-4">
                            <h6 class="mb-2" style="font-weight: 700;">{{ $firstMeditation->title }}</h6>
                            <p class="text-secondary mb-4" style="font-size: 14px; line-height: 1.5;">
                                {{ \Illuminate\Support\Str::limit(strip_tags($firstMeditation->description ?? ''), 90) }}
                            </p>
                            <a href="javascript:void(0)"
                                wire:click.prevent="selectDigitalProduct({{ $firstMeditation->id }})"
                                class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all"
                                onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.querySelector('.arrow_right').classList.add('ms-3')"
                                onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.querySelector('.arrow_right').classList.remove('ms-3')">
                                Listen Audio
                                <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                            </a>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-12">
                    <p class="text-secondary">No free meditation available yet.</p>
                </div>
                @endif
            </div>
            @endif
        </div>
    </section>
    <!-- Start Here Section End -->

    <!-- More Stories Section Begin -->
    <section class="latest spad" style="margin-top:-130px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title start-here-title-wrap">
                        <span class="start-here-kicker">Fresh Drops</span>
                        <h2 class="start-here-title">More Stories</h2>
                        <p class="start-here-subtitle">Catch the latest updates, moments, and behind-the-scenes posts.</p>
                    </div>
                </div>
            </div>
            <div class="row d-none d-md-flex">
                @foreach ($blogs as $blog)
                <div class="col-lg-4 col-md-6 mb-4" wire:key="more-stories-{{ $blog->id }}">
                    <div class="blog__item">
                        <div class="blog__item__pic set-bg" data-setbg="{{ $blog->image_url }}"
                            style="background-image: url('{{ $blog->image_url }}');">
                        </div>
                        <div class="blog__item__text">
                            <span style="color: #666666;"><img src="{{ asset('bootstrap/img/icon/calendar.png') }}" alt="">
                                {{ $blog->created_at->format('d F Y') }}</span>
                            <h5 style="color: #333333;">{{ $blog->title }}</h5>
                            <a href="javascript:void(0)" wire:click.prevent="openBlog({{ $blog->id }})"
                                class="text-primary fw-bold text-decoration-none shadow-hover"
                                style="color: #007bff;">
                                Read Blog
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="row d-block d-md-none">
                @php $firstStory = $blogs->first(); @endphp
                @if ($firstStory)
                <div class="col-12" wire:key="more-stories-mobile-{{ $firstStory->id }}">
                    <div class="blog__item">
                        <div class="blog__item__pic set-bg" data-setbg="{{ $firstStory->image_url }}"
                            style="background-image: url('{{ $firstStory->image_url }}'); min-height: 300px;">
                        </div>
                        <div class="blog__item__text">
                            <span style="color: #666666;"><img src="{{ asset('bootstrap/img/icon/calendar.png') }}" alt="">
                                {{ $firstStory->created_at->format('d F Y') }}</span>
                            <h5 style="color: #333333;">{{ $firstStory->title }}</h5>
                            <a href="javascript:void(0)" wire:click.prevent="openBlog({{ $firstStory->id }})"
                                class="text-primary fw-bold text-decoration-none shadow-hover"
                                style="color: #007bff;">
                                Read Blog
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </section>
    <!-- More Stories Section End -->

    <!-- Offers Section Begin -->
    <section id="offers-section" class="services spad" style="margin-top: -130px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title start-here-title-wrap">
                        <span class="start-here-kicker">Collaborate</span>
                        <h2 class="start-here-title">My Offers / Work With Me</h2>
                        <p class="start-here-subtitle">Explore partnerships, digital products, and merch.</p>
                    </div>
                </div>
            </div>
            <div class="row d-none d-md-flex">
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm border h-100 p-4" style="border-radius: 8px;">
                        <h4 class="mb-3" style="font-weight: 700;">Buenas</h4>
                        <p class="text-secondary mb-3">Official online gaming partner. Enjoy exclusive access through my link.</p>
                        <a href="https://bit.ly/CristBriand-buenasph" target="_blank" rel="noopener noreferrer"
                            class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all">
                            Open Buenas
                            <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm border h-100 p-4" style="border-radius: 8px;">
                        <h4 class="mb-3" style="font-weight: 700;">Digital Product</h4>
                        <p class="text-secondary mb-3">Free and paid digital tools you can access instantly.</p>
                        <a href="{{ route('digital-products') }}"
                            class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all">
                            Digital Products
                            <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-sm border h-100 p-4" style="border-radius: 8px;">
                        <h4 class="mb-3" style="font-weight: 700;">Merch</h4>
                        <p class="text-secondary mb-3">Shop the latest merch collection and essentials.</p>
                        <a href="{{ route('shop') }}"
                            class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all">
                            Shop Merch
                            <span class="arrow_right ms-2 transition-base" style="transition: 0.3s;"></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="row d-block d-md-none">
                <div class="col-12 mb-3">
                    <a href="https://bit.ly/CristBriand-buenasph" target="_blank" rel="noopener noreferrer"
                        class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all w-100 text-center">Buenas</a>
                </div>
                <div class="col-12 mb-3">
                    <a href="{{ route('digital-products') }}"
                        class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all w-100 text-center">Digital Product</a>
                </div>
                <div class="col-12">
                    <a href="{{ route('shop') }}"
                        class="primary-btn d-inline-block text-decoration-none shadow-sm transition-all w-100 text-center">Merch</a>
                </div>
            </div>
        </div>
    </section>
    <!-- Offers Section End -->

    <!-- About Me Section Begin -->
    <section class="services spad" style="margin-top: -160px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title start-here-title-wrap">
                        <span class="start-here-kicker">My Story</span>
                        <h2 class="start-here-title">About Me</h2>
                        <p class="start-here-subtitle">A quick look at who I am and what I create.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 mx-auto text-center">
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
                    <div class="section-title start-here-title-wrap">
                        <span class="start-here-kicker">Get In Touch</span>
                        <h2 class="start-here-title">Contact</h2>
                        <!-- <p class="start-here-subtitle">Reach out for inquiries, collabs, or business opportunities.</p> -->
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

    @if ($highlightModalVideoUrl)
    <div wire:key="highlight-video-modal"
        style="position: fixed; inset: 0; background: rgba(0, 0, 0, 0.75); z-index: 99999; display: flex; align-items: center; justify-content: center; padding: 20px;">
        <div wire:click.stop
            style="background: #000; width: 100%; max-width: 900px; border-radius: 12px; overflow: hidden; box-shadow: 0 18px 45px rgba(0, 0, 0, 0.45);">
            <div
                style="display: flex; justify-content: space-between; align-items: center; padding: 14px 18px; background: #111; color: #fff;">
                <h5 style="margin: 0; color: #fff;">{{ $highlightModalTitle ?: 'Highlighted Video' }}</h5>
                <button type="button" wire:click="closeHighlightModal"
                    style="background: transparent; border: none; color: #fff; font-size: 24px; line-height: 1; cursor: pointer;">
                    &times;
                </button>
            </div>
            <div style="padding: 0;">
                <video id="highlight-modal-video" wire:key="highlight-player-{{ md5($highlightModalVideoUrl) }}" controls autoplay muted playsinline
                    src="{{ $highlightModalVideoUrl }}"
                    preload="auto"
                    style="width: 100%; max-height: 80vh; display: block;">
                    <source src="{{ $highlightModalVideoUrl }}" @if($highlightModalVideoMime) type="{{ $highlightModalVideoMime }}" @endif>
                    Your browser cannot play this video format.
                </video>
                <div style="padding: 10px 16px; background: #111;">
                    <a href="{{ $highlightModalVideoUrl }}" target="_blank" rel="noopener noreferrer"
                        style="color: #f5f5f5; text-decoration: underline; font-size: 13px;">
                        Video not playing? Open video in a new tab
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

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

        .start-here-title-wrap {
            position: relative;
            margin-bottom: 10px;
        }

        .start-here-kicker {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 999px;
            background: rgba(229, 54, 55, 0.12);
            color: #e53637;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .start-here-title {
            position: relative;
            display: inline-block;
            margin-bottom: 8px;
            padding-bottom: 8px;
            font-weight: 800;
            letter-spacing: 0.4px;
            color: #111111;
        }

        .start-here-title::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 0;
            width: 72px;
            height: 4px;
            border-radius: 999px;
            background: linear-gradient(90deg, #e53637 0%, #ff8f5a 100%);
        }

        .start-here-subtitle {
            margin: 6px 0 0;
            color: #666666;
            font-size: 15px;
        }
    </style>

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

</div>

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
            var videoSrc = "{{ asset('videos/Brader-Skate.mp4') }}";
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

    function tryPlayHighlightModalVideo() {
        var modalVideo = document.getElementById('highlight-modal-video');
        if (!modalVideo) {
            return;
        }

        try {
            modalVideo.load();
        } catch (e) {}

        var playPromise = modalVideo.play();
        if (playPromise !== undefined) {
            playPromise.catch(function() {});
        }
    }

    // Re-attempt modal playback after Livewire opens it.
    document.addEventListener('livewire:init', function() {
        if (typeof Livewire !== 'undefined' && typeof Livewire.on === 'function') {
            Livewire.on('highlight-modal-opened', function() {
                setTimeout(tryPlayHighlightModalVideo, 30);
                setTimeout(tryPlayHighlightModalVideo, 150);
            });
        }
    });
</script>
</div>
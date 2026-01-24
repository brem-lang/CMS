<div>
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-blog set-bg" data-setbg="{{ asset('img/services.webp') }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2>My Blog</h2>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Blog Section Begin -->
    <section class="blog spad">
        <div class="container">
            <div class="row">
                @forelse($blogs as $blog)
                    <div class="col-lg-4 col-md-6 col-sm-6" wire:key="blog-{{ $blog->id }}">
                        <div class="blog__item">
                            <div class="blog__item__pic set-bg" data-setbg="{{ $blog->image_url }}"
                                style="background-image: url('{{ $blog->image_url }}');">
                            </div>
                            <div class="blog__item__text">
                                <span><img src="{{ asset('bootstrap/img/icon/calendar.png') }}" alt="">
                                    {{ $blog->created_at->format('d F Y') }}</span>
                                <h5>{{ $blog->title }}</h5>
                                <a href="#" wire:click.prevent="openBlog({{ $blog->id }})">Read More</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-lg-12">
                        <p class="text-center">No blog posts available at the moment.</p>
                    </div>
                @endforelse
            </div>
            @if ($blogs->hasPages())
                <div class="row">
                    <div class="col-lg-12">
                        {{ $blogs->links('vendor.livewire.custom-shop') }}
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- Blog Section End -->
</div>

<script>
    // Function to set background images
    function setBackgroundImages() {
        $('.blog__item__pic.set-bg').each(function() {
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

    // Set background images on Livewire init
    document.addEventListener('livewire:init', () => {
        setBackgroundImages();
    });

    // Re-set background images after Livewire updates
    document.addEventListener('livewire:update', () => {
        setBackgroundImages();
    });
</script>

<div>
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Digital Products</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Home</a>
                            <span>Digital Products</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="shop spad">
        <div class="container">
            <div class="row" style="margin-top:-50px;">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Digital Products</h2>
                        <h5 class="mt-4 text-secondary">
                            Start free. Go Deeper when ready.
                        </h5>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-3 mb-3 mb-lg-0 p-6">
                    <div class="shop__sidebar">
                        <div class="shop__sidebar__search">
                            <form wire:submit.prevent>
                                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search...">
                                <button type="submit"><span class="icon_search"></span></button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-9">
                    <div class="shop__product__option">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="shop__product__option__left">
                                    <p>Showing {{ $digitalProducts->firstItem() ?? 0 }} to
                                        {{ $digitalProducts->lastItem() ?? 0 }} of
                                        {{ $digitalProducts->total() }}
                                        {{ Str::plural('result', $digitalProducts->total()) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @forelse($digitalProducts as $product)
                            <div class="col-6 col-lg-4" wire:key="digital-product-{{ $product->id }}">
                                <div class="product__item">
                                    <a href="{{ route('digital-product.view', $product->id) }}"
                                        class="d-block text-decoration-none">
                                        <div class="product__item__pic set-bg rounded shadow-sm border-0 d-flex align-items-center justify-content-center position-relative"
                                            @if ($product->thumbnail_url) data-setbg="{{ $product->thumbnail_url }}"
                                                style="background-image: url('{{ $product->thumbnail_url }}'); min-height: 220px; background-size: cover; background-position: center; position: relative;"
                                            @else
                                                style="min-height: 220px; align-items: center; justify-content: center; position: relative;" @endif
                                            onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.classList.add('border', 'border-primary'); this.querySelector('.digital-quick-view').classList.remove('opacity-0');"
                                            onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.classList.remove('border', 'border-primary'); this.querySelector('.digital-quick-view').classList.add('opacity-0');">
                                            @if (!$product->thumbnail_url)
                                                <span class="text-muted"><i class="fa fa-file-o fa-3x"></i></span>
                                            @endif
                                            <div class="digital-quick-view position-absolute opacity-0 d-none d-md-flex align-items-center justify-content-center w-100 h-100 transition-opacity"
                                                style="top: 0; left: 0; background: rgba(0,0,0,0.2); border-radius: inherit;">
                                                <span class="btn btn-light btn-sm shadow-sm rounded-pill px-3">Quick
                                                    View</span>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="product__item__text">
                                        <h6>{{ Str::limit(strip_tags($product->title), 20) }}</h6>
                                        <p class="small text-muted mb-0" style="font-size: 0.85rem; line-height: 1.3;">
                                            {{ Str::limit(strip_tags($product->description), 20) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-lg-12">
                                <div class="text-center py-5">
                                    <p>No digital products found.</p>
                                    @if ($search)
                                        <a href="#" wire:click="$set('search', '')" class="primary-btn">Clear
                                            search</a>
                                    @endif
                                </div>
                            </div>
                        @endforelse
                    </div>
                    @if ($digitalProducts->hasPages())
                        <div class="row">
                            <div class="col-lg-12">
                                {{ $digitalProducts->links('vendor.livewire.custom-shop') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    document.addEventListener('livewire:init', function() {
        $('.set-bg').each(function() {
            var bg = $(this).data('setbg');
            if (bg) {
                var existingStyle = $(this).attr('style') || '';
                existingStyle = existingStyle.replace(/background-image\s*:\s*[^;]+;?/gi, '').trim();
                $(this).attr('style', existingStyle + (existingStyle ? ' ' : '') +
                    'background-image: url(' + bg + ');');
            }
        });
    });
    document.addEventListener('livewire:update', function() {
        $('.set-bg').each(function() {
            var bg = $(this).data('setbg');
            if (bg) {
                var existingStyle = $(this).attr('style') || '';
                existingStyle = existingStyle.replace(/background-image\s*:\s*[^;]+;?/gi, '').trim();
                $(this).attr('style', existingStyle + (existingStyle ? ' ' : '') +
                    'background-image: url(' + bg + ');');
            }
        });
    });
</script>

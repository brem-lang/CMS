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
                                    <div class="product__item__pic set-bg rounded shadow-sm border-0 d-flex align-items-center justify-content-center"
                                        @if ($product->thumbnail_url) data-setbg="{{ $product->thumbnail_url }}"
                                            style="background-image: url('{{ $product->thumbnail_url }}'); min-height: 220px; background-size: cover; background-position: center;"
                                        @else
                                            style="min-height: 220px; background: #f0f0f0; align-items: center; justify-content: center;" @endif
                                        onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.classList.add('border', 'border-primary')"
                                        onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.classList.remove('border', 'border-primary')">
                                        @if (!$product->thumbnail_url)
                                            <span class="text-muted"><i class="fa fa-file-o fa-3x"></i></span>
                                        @endif
                                    </div>
                                    <div class="product__item__text">
                                        <h6> {{ Str::limit(strip_tags($product->title), 20) }}</h6>
                                        <p class="small text-muted mb-2" style="font-size: 0.85rem; line-height: 1.3;">
                                            {{ Str::limit(strip_tags($product->description), 20) }}
                                        </p>
                                        <div class="mt-2">
                                            @php
                                                $isPdf = $product->file_type === 'pdf';
                                                $label = $product->is_free
                                                    ? 'Download Free ' . ($isPdf ? 'PDF' : 'Audio')
                                                    : 'Get ' . ($isPdf ? 'PDF' : 'Audio');
                                                $icon = $isPdf ? 'fa-file-pdf-o' : 'fa-headphones';
                                                $freeHref = route('digital-product.download', $product->id);
                                                $paidHref = route('digital-product.view', $product->id);
                                            @endphp
                                            <a href="{{ $product->is_free ? $freeHref : $paidHref }}"
                                                class="btn btn-digital-get w-100 border-0 rounded-pill py-2 px-3 text-white fw-semibold d-inline-flex align-items-center justify-content-center gap-2 text-decoration-none"
                                                style="
                                                    font-size: 0.9rem;
                                                    cursor: pointer;
                                                    background: {{ $product->is_free ? 'linear-gradient(135deg, #28a745 0%, #20c997 100%)' : 'linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%)' }};
                                                    box-shadow: 0 3px 10px {{ $product->is_free ? 'rgba(40, 167, 69, 0.35)' : 'rgba(13, 110, 253, 0.35)' }};
                                                    transition: transform 0.2s, box-shadow 0.2s;
                                                "
                                                @if ($product->is_free) target="_blank" rel="noopener" @endif
                                                onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 18px {{ $product->is_free ? 'rgba(40, 167, 69, 0.45)' : 'rgba(13, 110, 253, 0.45)' }}';"
                                                onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 3px 10px {{ $product->is_free ? 'rgba(40, 167, 69, 0.35)' : 'rgba(13, 110, 253, 0.35)' }}';">
                                                <i class="fa {{ $product->is_free ? 'fa-download' : $icon }}" aria-hidden="true"></i>
                                                <span>{{ $label }}</span>
                                            </a>
                                        </div>
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

<?php

namespace App\Livewire;

use App\Models\Blog;
use App\Models\DigitalProduct;
use App\Models\MyContent;
use App\Models\Product;
use App\Services\CartService;
use App\View\Components\Layout\App;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(App::class)]
class HomePage extends Component
{
    public $products;

    public $digitalProducts;

    public $blogs;

    public $featuredBlog;

    public $highlightContents;

    public $freeMeditations;

    public ?string $highlightModalVideoUrl = null;

    public ?string $highlightModalTitle = null;

    public ?string $highlightModalVideoMime = null;

    public function mount()
    {
        $this->products = Product::where('status', true)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $this->digitalProducts = DigitalProduct::where('is_active', true)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $this->freeMeditations = DigitalProduct::query()
            ->where('is_active', true)
            ->where('is_free', true)
            ->where('file_type', 'audio')
            ->latest()
            ->limit(3)
            ->get();

        $this->featuredBlog = Blog::query()
            ->where('status', true)
            ->where('is_highlight', true)
            ->latest()
            ->first();

        $this->blogs = Blog::query()
            ->where('status', true)
            ->when($this->featuredBlog, fn ($query) => $query->whereKeyNot($this->featuredBlog->getKey()))
            ->latest()
            ->limit(3)
            ->get();

        $this->highlightContents = MyContent::query()
            ->where('highlights', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->limit(3)
            ->get();
    }

    public function selectProduct($id)
    {
        return redirect()->route('product.view', $id);
    }

    public function selectDigitalProduct($id)
    {
        return redirect()->route('digital-product.view', $id);
    }

    public function openBlog($id)
    {
        return redirect()->route('blog.view', $id);
    }

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        app(CartService::class)->addToCart($id, 1);
        $this->dispatch('cartUpdated', message: 'Product added to cart successfully!');
    }

    public function openHighlightModal(int $contentId): void
    {
        $content = MyContent::query()->find($contentId);

        if (! $content || empty($content->video_url)) {
            return;
        }

        $this->highlightModalVideoUrl = $content->video_url;
        $this->highlightModalTitle = $content->title;
        $this->highlightModalVideoMime = $this->detectVideoMimeType($content->video_path, $content->video_url);
        $this->dispatch('highlight-modal-opened');
    }

    public function closeHighlightModal(): void
    {
        $this->highlightModalVideoUrl = null;
        $this->highlightModalTitle = null;
        $this->highlightModalVideoMime = null;
    }

    protected function detectVideoMimeType(?string $path, ?string $url): ?string
    {
        $candidate = strtolower((string) ($path ?: $url));
        $candidate = strtok($candidate, '?') ?: $candidate;

        if (str_ends_with($candidate, '.mp4')) {
            return 'video/mp4';
        }

        if (str_ends_with($candidate, '.webm')) {
            return 'video/webm';
        }

        if (str_ends_with($candidate, '.mov')) {
            return 'video/quicktime';
        }

        if (str_ends_with($candidate, '.avi')) {
            return 'video/x-msvideo';
        }

        return null;
    }

    public function render()
    {
        return view('livewire.home-page');
    }
}

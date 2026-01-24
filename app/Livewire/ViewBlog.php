<?php

namespace App\Livewire;

use App\Models\Blog as BlogModel;
use App\View\Components\Layout\App;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(App::class)]
class ViewBlog extends Component
{
    public $blog;

    public function mount($id)
    {
        $this->blog = BlogModel::where('id', $id)
            ->where('status', true)
            ->with('user')
            ->firstOrFail();
    }

    public function render()
    {
        // Get previous and next blogs
        $previousBlog = BlogModel::where('status', true)
            ->where('id', '<', $this->blog->id)
            ->orderBy('id', 'desc')
            ->first();

        $nextBlog = BlogModel::where('status', true)
            ->where('id', '>', $this->blog->id)
            ->orderBy('id', 'asc')
            ->first();

        return view('livewire.view-blog', [
            'previousBlog' => $previousBlog,
            'nextBlog' => $nextBlog,
        ]);
    }
}

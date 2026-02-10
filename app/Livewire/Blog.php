<?php

namespace App\Livewire;

use App\Models\Blog as BlogModel;
use App\View\Components\Layout\App;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout(App::class)]
class Blog extends Component
{
    use WithPagination;

    public function mount()
    {
        // Any initialization if needed
    }

    public function openBlog($id)
    {
        return redirect()->route('blog.view', $id);
    }

    public function render()
    {
        $blogs = BlogModel::where('status', true)
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('livewire.blog', [
            'blogs' => $blogs,
        ]);
    }
}

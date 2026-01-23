<?php

namespace App\Livewire;

use App\View\Components\Layout\App;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(App::class)]
class HomePage extends Component
{
    public function render()
    {
        return view('livewire.home-page');
    }
}

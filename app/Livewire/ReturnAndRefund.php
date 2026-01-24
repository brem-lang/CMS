<?php

namespace App\Livewire;

use App\View\Components\Layout\App;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(App::class)]
class ReturnAndRefund extends Component
{
    public function render()
    {
        return view('livewire.return-and-refund');
    }
}

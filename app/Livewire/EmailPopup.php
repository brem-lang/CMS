<?php

namespace App\Livewire;

use Livewire\Component;

class EmailPopup extends Component
{
    public function render()
    {
        return view('livewire.email-popup', [
            'subscribeUrl' => route('newsletter.subscribe'),
            'csrfToken' => csrf_token(),
        ]);
    }
}

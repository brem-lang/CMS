<?php

namespace App\Livewire;

use App\Livewire\Forms\LoginForm;
use App\View\Components\Layout\App;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(App::class)]
class Login extends Component
{
    public LoginForm $form;

    public function mount()
    {
        // If user is already logged in, redirect to home
        if (Auth::check()) {
            return redirect()->route('home');
        }
    }

    public function login()
    {
        $this->form->authenticate();

        session()->regenerate();

        // Redirect to intended page or home
        return redirect()->intended(route('home'));
    }

    public function render()
    {
        return view('livewire.login');
    }
}

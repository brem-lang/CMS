<?php

namespace App\Livewire;

use App\Jobs\SendContactFormEmail;
use App\Models\User;
use App\View\Components\Layout\App;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout(App::class)]
class Contact extends Component
{
    public $name = '';

    public $email = '';

    public $phone = '';

    public $message = '';

    public $success = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'message' => 'required|string|max:5000',
    ];

    protected $messages = [
        'name.required' => 'Please enter your name.',
        'email.required' => 'Please enter your email address.',
        'email.email' => 'Please enter a valid email address.',
        'message.required' => 'Please enter your message.',
        'message.max' => 'Your message is too long. Maximum 5000 characters.',
    ];

    public function submit()
    {
        $this->validate();

        try {
            // Check if admin users exist
            $adminUsers = User::where('role', 'admin')->get();

            if ($adminUsers->isEmpty()) {
                $this->addError('email', 'No admin users found. Please contact support directly.');

                return;
            }

            // Dispatch job to send email asynchronously
            // Use onQueue to ensure it goes to the queue even if default is sync
            SendContactFormEmail::dispatch(
                $this->name,
                $this->email,
                $this->phone,
                $this->message
            );

            // Reset form and show success message
            $this->reset(['name', 'email', 'phone', 'message']);
            $this->success = true;
            $this->dispatch('contact-form-submitted');

            // Reset success message after 5 seconds
            $this->dispatch('reset-success-message');
        } catch (\Exception $e) {
            $this->addError('email', 'Failed to send message. Please try again later or contact us directly.');
            \Log::error('Contact form error: '.$e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.contact');
    }
}

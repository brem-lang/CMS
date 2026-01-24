<?php

use App\Models\User;
use App\Services\CartService;
use App\View\Components\Layout\App;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout(App::class)] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'user';

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        // Migrate guest cart to database if user had items in session
        app(CartService::class)->migrateGuestCartToDatabase($user->id);

        $this->redirect(route('home', absolute: false), navigate: false);
    }
}; ?>

<div>
    <!-- Register Section Begin -->
    <section class="contact spad" style="padding-top: 50px; padding-bottom: 100px;">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="contact__form"
                        style="background: #fff; padding: 50px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                        <div class="section-title text-center" style="margin-bottom: 40px;">
                            <span>Create Account</span>
                            <h2>Register Now</h2>
                            <p>Please fill in your information to create your account</p>
                        </div>

                        @if (session('message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert"
                                style="margin-bottom: 30px;">
                                <i class="fa fa-check-circle"></i> {{ session('message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form wire:submit="register">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group" style="margin-bottom: 25px;">
                                        <label for="name"
                                            style="display: block; margin-bottom: 8px; color: #111111; font-weight: 600;">
                                            Full Name <span style="color: #e53637;">*</span>
                                        </label>
                                        <input type="text" id="name" wire:model="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="Enter your full name"
                                            style="width: 100%; padding: 12px 15px; border: 1px solid #e5e5e5; border-radius: 5px; font-size: 16px; transition: all 0.3s;"
                                            onfocus="this.style.borderColor='#e53637'; this.style.boxShadow='0 0 0 0.2rem rgba(229, 54, 55, 0.25)'"
                                            onblur="this.style.borderColor='#e5e5e5'; this.style.boxShadow='none'"
                                            required autofocus autocomplete="name">
                                        @error('name')
                                            <div class="invalid-feedback"
                                                style="display: block; color: #e53637; margin-top: 5px;">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group" style="margin-bottom: 25px;">
                                        <label for="email"
                                            style="display: block; margin-bottom: 8px; color: #111111; font-weight: 600;">
                                            Email Address <span style="color: #e53637;">*</span>
                                        </label>
                                        <input type="email" id="email" wire:model="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="Enter your email"
                                            style="width: 100%; padding: 12px 15px; border: 1px solid #e5e5e5; border-radius: 5px; font-size: 16px; transition: all 0.3s;"
                                            onfocus="this.style.borderColor='#e53637'; this.style.boxShadow='0 0 0 0.2rem rgba(229, 54, 55, 0.25)'"
                                            onblur="this.style.borderColor='#e5e5e5'; this.style.boxShadow='none'"
                                            required autocomplete="username">
                                        @error('email')
                                            <div class="invalid-feedback"
                                                style="display: block; color: #e53637; margin-top: 5px;">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group" style="margin-bottom: 25px;">
                                        <label for="password"
                                            style="display: block; margin-bottom: 8px; color: #111111; font-weight: 600;">
                                            Password <span style="color: #e53637;">*</span>
                                        </label>
                                        <div style="position: relative;">
                                            <input type="password" id="password" wire:model="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Enter your password"
                                                style="width: 100%; padding: 12px 15px; border: 1px solid #e5e5e5; border-radius: 5px; font-size: 16px; transition: all 0.3s;"
                                                onfocus="this.style.borderColor='#e53637'; this.style.boxShadow='0 0 0 0.2rem rgba(229, 54, 55, 0.25)'"
                                                onblur="this.style.borderColor='#e5e5e5'; this.style.boxShadow='none'"
                                                required autocomplete="new-password">
                                            <i class="fa fa-eye" id="togglePassword"
                                                style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666;"
                                                onclick="togglePasswordVisibility('password', 'togglePassword')"></i>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback"
                                                style="display: block; color: #e53637; margin-top: 5px;">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group" style="margin-bottom: 25px;">
                                        <label for="password_confirmation"
                                            style="display: block; margin-bottom: 8px; color: #111111; font-weight: 600;">
                                            Confirm Password <span style="color: #e53637;">*</span>
                                        </label>
                                        <div style="position: relative;">
                                            <input type="password" id="password_confirmation" wire:model="password_confirmation"
                                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                                placeholder="Confirm your password"
                                                style="width: 100%; padding: 12px 15px; border: 1px solid #e5e5e5; border-radius: 5px; font-size: 16px; transition: all 0.3s;"
                                                onfocus="this.style.borderColor='#e53637'; this.style.boxShadow='0 0 0 0.2rem rgba(229, 54, 55, 0.25)'"
                                                onblur="this.style.borderColor='#e5e5e5'; this.style.boxShadow='none'"
                                                required autocomplete="new-password">
                                            <i class="fa fa-eye" id="togglePasswordConfirmation"
                                                style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666;"
                                                onclick="togglePasswordVisibility('password_confirmation', 'togglePasswordConfirmation')"></i>
                                        </div>
                                        @error('password_confirmation')
                                            <div class="invalid-feedback"
                                                style="display: block; color: #e53637; margin-top: 5px;">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <button type="submit" class="site-btn" wire:loading.attr="disabled"
                                        style="width: 100%; padding: 15px; font-size: 16px; font-weight: 600; border: none; cursor: pointer; transition: all 0.3s;"
                                        onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                                        <span wire:loading.remove wire:target="register">Register</span>
                                        <span wire:loading wire:target="register">
                                            <i class="fa fa-spinner fa-spin"></i> Registering...
                                        </span>
                                    </button>
                                </div>
                                <div class="col-lg-12 text-center" style="margin-top: 30px;">
                                    <p style="color: #666; margin: 0;">
                                        Already have an account? <a href="{{ route('login') }}"
                                            style="color: #e53637; text-decoration: none; font-weight: 600;">Sign In</a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Register Section End -->
</div>

<script>
    function togglePasswordVisibility(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>

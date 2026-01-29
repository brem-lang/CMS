<div>
    <!-- Login Section Begin -->
    <section class="contact spad" style="padding-top: 50px; padding-bottom: 100px;">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="contact__form"
                        style="background: #fff; padding: 50px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                        <div class="section-title text-center" style="margin-bottom: 40px;">
                            <span>Welcome Back</span>
                            <h2>Login to Your Account</h2>
                            <p>Please enter your credentials to access your account</p>
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

                        <form wire:submit="login">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group" style="margin-bottom: 25px;">
                                        <label for="email"
                                            style="display: block; margin-bottom: 8px; color: #111111; font-weight: 600;">
                                            Email Address <span style="color: #e53637;">*</span>
                                        </label>
                                        <input type="email" id="email" wire:model="form.email"
                                            class="form-control @error('form.email') is-invalid @enderror"
                                            placeholder="Enter your email"
                                            style="width: 100%; padding: 12px 15px; border: 1px solid #e5e5e5; border-radius: 5px; font-size: 16px; transition: all 0.3s;"
                                            onfocus="this.style.borderColor='#e53637'; this.style.boxShadow='0 0 0 0.2rem rgba(229, 54, 55, 0.25)'"
                                            onblur="this.style.borderColor='#e5e5e5'; this.style.boxShadow='none'">
                                        @error('form.email')
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
                                            <input type="password" id="password" wire:model="form.password"
                                                class="form-control @error('form.password') is-invalid @enderror"
                                                placeholder="Enter your password"
                                                style="width: 100%; padding: 12px 15px; border: 1px solid #e5e5e5; border-radius: 5px; font-size: 16px; transition: all 0.3s;"
                                                onfocus="this.style.borderColor='#e53637'; this.style.boxShadow='0 0 0 0.2rem rgba(229, 54, 55, 0.25)'"
                                                onblur="this.style.borderColor='#e5e5e5'; this.style.boxShadow='none'">
                                            <i class="fa fa-eye" id="togglePassword"
                                                style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666;"
                                                onclick="togglePasswordVisibility()"></i>
                                        </div>
                                        @error('form.password')
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
                                        <span wire:loading.remove wire:target="login">Login</span>
                                        <span wire:loading wire:target="login">
                                            <i class="fa fa-spinner fa-spin"></i> Logging in...
                                        </span>
                                    </button>
                                </div>
                                <div class="col-lg-12">
                                    <div style="display: flex; align-items: center; margin: 20px 0;">
                                        <div style="flex: 1; height: 1px; background-color: #e5e5e5;"></div>
                                        <span style="padding: 0 15px; color: #666; font-size: 14px;">OR</span>
                                        <div style="flex: 1; height: 1px; background-color: #e5e5e5;"></div>
                                    </div>
                                    <a href="{{ route('auth.google') }}" class="site-btn"
                                        style="width: 100%; padding: 15px; font-size: 16px; font-weight: 600; border: none; cursor: pointer; transition: all 0.3s; background-color: #4285F4; color: white; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 10px;"
                                        onmouseover="this.style.opacity='0.9'; this.style.backgroundColor='#357ae8';"
                                        onmouseout="this.style.opacity='1'; this.style.backgroundColor='#4285F4';">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                                fill="#4285F4" />
                                            <path
                                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                                fill="#34A853" />
                                            <path
                                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                                fill="#FBBC05" />
                                            <path
                                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                                fill="#EA4335" />
                                        </svg>
                                        <span>Login with Google</span>
                                    </a>
                                </div>
                                <div class="col-lg-12 text-center" style="margin-top: 30px;">
                                    <p style="color: #666; margin: 0;">
                                        Don't have an account? <a href="{{ route('register') }}"
                                            style="color: #e53637; text-decoration: none; font-weight: 600;">Sign Up</a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Login Section End -->
</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePassword');

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

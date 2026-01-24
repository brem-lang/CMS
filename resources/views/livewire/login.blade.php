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

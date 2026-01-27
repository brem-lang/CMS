<div>
    <!-- Map Begin -->
    <div class="map">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d4944.211595144529!2d125.60213267587153!3d7.068607416587024!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x32f91642bc6ac679%3A0x92dbcc89a3e2bb3b!2sHannah&#39;s%20Jewelry%20%26%20Pawnshop%20Ilustre%20Branch!5e1!3m2!1sen!2sph!4v1769221485172!5m2!1sen!2sph"
            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <!-- Map End -->

    <!-- Contact Section Begin -->
    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="contact__text">
                        <div class="section-title">
                            <span>Information</span>
                            <h2>Contact Us</h2>
                            <p>Have questions? We’d love to hear from you. Send us a message and we’ll respond as soon
                                as possible. Fill out the form and our team will get back to you within 24 hours.</p>
                        </div>
                        <ul>
                            <li>
                                <div class="row" style="margin-bottom: 15px;">
                                    <div class="col-2"
                                        style="display: flex; align-items: flex-start; justify-content: center; padding-top: 5px;">
                                        <i class="fa fa-map-marker" style="font-size: 24px; color: #e53637;"></i>
                                    </div>
                                    <div class="col-10">
                                        <p style="margin: 0;">Milagros Building Ilustre St., Davao City
                                            Hannah’s Pawnshop</p>
                                    </div>
                                </div>
                                <div class="row" style="margin-bottom: 15px;">
                                    <div class="col-2"
                                        style="display: flex; align-items: flex-start; justify-content: center; padding-top: 5px;">
                                        <i class="fa fa-phone" style="font-size: 24px; color: #e53637;"></i>
                                    </div>
                                    <div class="col-10">
                                        <p style="margin: 0;">+639 995 234 1590</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-2"
                                        style="display: flex; align-items: flex-start; justify-content: center; padding-top: 5px;">
                                        <i class="fa fa-envelope" style="font-size: 24px; color: #e53637;"></i>
                                    </div>
                                    <div class="col-10">
                                        <p style="margin: 0;">cboncada@gmail.com</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="contact__form">
                        @if($success)
                            <div class="alert alert-success alert-dismissible fade show" role="alert"
                                style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
                                <strong>Success!</strong> Your message has been sent successfully. We'll get back to you soon!
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                                    wire:click="$set('success', false)"
                                    style="float: right; background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert"
                                style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
                                <strong>Error!</strong> Please fix the following errors:
                                <ul style="margin: 10px 0 0 20px;">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                                    onclick="this.parentElement.remove()"
                                    style="float: right; background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
                            </div>
                        @endif

                        <form wire:submit.prevent="submit">
                            <div class="row">
                                <div class="col-lg-6">
                                    <input type="text"
                                        wire:model="name"
                                        class="form-control border-secondary shadow-none transition-all @error('name') border-danger @enderror"
                                        placeholder="Your Name" 
                                        onfocus="this.classList.add('border-dark', 'shadow-sm')"
                                        onblur="this.classList.remove('border-dark', 'shadow-sm')"
                                        style="transition: 0.3s;">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-lg-6">
                                    <input type="email"
                                        wire:model="email"
                                        class="form-control border-secondary shadow-none transition-all @error('email') border-danger @enderror"
                                        placeholder="Your Email" 
                                        onfocus="this.classList.add('border-dark', 'shadow-sm')"
                                        onblur="this.classList.remove('border-dark', 'shadow-sm')"
                                        style="transition: 0.3s;">
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-lg-12">
                                    <input type="text"
                                        wire:model="phone"
                                        class="form-control border-secondary shadow-none transition-all @error('phone') border-danger @enderror"
                                        placeholder="Your Phone (Optional)" 
                                        onfocus="this.classList.add('border-dark', 'shadow-sm')"
                                        onblur="this.classList.remove('border-dark', 'shadow-sm')"
                                        style="transition: 0.3s; margin-top: 15px;">
                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="col-lg-12">
                                    <textarea 
                                        wire:model="message"
                                        class="form-control border-secondary shadow-none transition-all @error('message') border-danger @enderror" 
                                        placeholder="Your Message" 
                                        rows="4"
                                        onfocus="this.classList.add('border-dark', 'shadow-sm')" 
                                        onblur="this.classList.remove('border-dark', 'shadow-sm')"
                                        style="transition: 0.3s; margin-top: 15px;"></textarea>
                                    @error('message')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                    <button type="submit"
                                        wire:loading.attr="disabled"
                                        class="site-btn btn primary-btn text-white shadow-sm border-0 transition-all mt-3"
                                        onmouseover="this.classList.replace('shadow-sm', 'shadow-lg'); this.style.transform='translateY(-2px)'"
                                        onmouseout="this.classList.replace('shadow-lg', 'shadow-sm'); this.style.transform='translateY(0)'"
                                        style="transition: all 0.3s ease; opacity: 1;">
                                        <span wire:loading.remove wire:target="submit">Send Message</span>
                                        <span wire:loading wire:target="submit">
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            Sending...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Section End -->
</div>

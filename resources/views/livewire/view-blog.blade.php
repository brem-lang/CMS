<div>
    <!-- Blog Details Hero Begin -->
    <section class="blog-hero spad">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-9 text-center">
                    <div class="blog__hero__text">
                        <h2>{{ $blog->title }}</h2>
                        <ul>
                            <li>By {{ $blog->user->name }}</li>
                            <li>{{ $blog->created_at->format('F d, Y') }}</li>
                            <li>{{ $blog->created_at->diffForHumans() }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Hero End -->

    <!-- Blog Details Section Begin -->
    <section class="blog-details spad">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-12">
                    <div class="blog__details__pic">
                        <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="img-fluid"
                            style="width: 100%; height: auto; border-radius: 5px;">
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="blog__details__content">
                        <div class="blog__details__share">
                            <span>share</span>
                            <ul>
                                <li><a href="https://www.facebook.com" target="_blank"><i
                                            class="fa fa-facebook"></i></a></li>
                                <li><a href="https://www.twitter.com" class="twitter" target="_blank"><i
                                            class="fa fa-twitter"></i></a>
                                </li>
                                <li><a href="https://www.youtube.com" class="youtube" target="_blank"><i
                                            class="fa fa-youtube-play"></i></a>
                                </li>
                                <li><a href="https://www.instagram.com" class="linkedin" target="_blank"><i
                                            class="fa fa-instagram"></i></a>
                                </li>
                            </ul>
                        </div>
                        <div class="blog__details__text">
                            {!! $blog->content !!}
                        </div>
                        <div class="blog__details__option">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6">

                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <div class="blog__details__tags">
                                        <a href="{{ route('blog') }}">#Blog</a>
                                        <a href="{{ route('blog') }}">#News</a>
                                        <a href="{{ route('blog') }}">#{{ date('Y') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if ($previousBlog || $nextBlog)
                            <div class="blog__details__btns">
                                <div class="row">
                                    @if ($previousBlog)
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <a href="{{ route('blog.view', $previousBlog->id) }}"
                                                class="blog__details__btns__item">
                                                <p><span class="arrow_left"></span> Previous Post</p>
                                                <h5>{{ Str::limit($previousBlog->title, 50) }}</h5>
                                            </a>
                                        </div>
                                    @endif
                                    @if ($nextBlog)
                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                            <a href="{{ route('blog.view', $nextBlog->id) }}"
                                                class="blog__details__btns__item blog__details__btns__item--next">
                                                <p>Next Post <span class="arrow_right"></span></p>
                                                <h5>{{ Str::limit($nextBlog->title, 50) }}</h5>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Section End -->
</div>

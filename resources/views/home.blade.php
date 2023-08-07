<x-layout title="Home">

    @if ($libraries->count() == 0 && $blogs->count() == 0)
        <div class="no_records">
            {{ __('layout.no_records')}}
        </div>
    @else
        <div class="sliders">
            @if ($blogs->count() > 0)
                <div class="glide_container glide-2">
                    <div class="header">
                        <a href="/blog">
                            <span>
                                {{ __('layout.blog') }}
                                <i class="fas fa-blog"></i>
                            </span>
                        </a>
                        <div class="slider-navigation">
                            <!-- Next and Previous Buttons -->
                            <button class="glide__arrow glide__arrow--prev" data-glide-dir="<"><i class="fas fa-chevron-left"></i></button>
                            <button class="glide__arrow glide__arrow--next" data-glide-dir=">"><i class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>
                    <div class="glide">
                        <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                            @if($blogs->count() > 0)
                                @foreach ($blogs as $blog)
                                    <li class="glide__slide">
                                        <a href="/blog/{{$blog->id}}">
                                            <div class="image-container">
                                                @if($blog->thumbnail)
                                                    <img src="{{ asset('storage/'. $blog->thumbnail) }}" alt="">
                                                @else
                                                    <div class="no-image">
                                                        <i class="fal fa-image"></i>
                                                    </div>
                                                @endif
                                                <div class="overlay">
                                                    {{ substr($blog->body, 0,300) }}
                                                    <p class="read-more">{{ __('layout.read_more') }}</p>
                                                </div>
                                            </div>
                                            <div class="meta">
                                                <p class="title">{{ $blog->title }}</p>
                                                <p class="time">
                                                    <i class="fal fa-clock"></i>
                                                    {{ $blog->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                        </div>
                    </div>
                </div>
            @endif
            @if($libraries->count() > 0)
                <div class="glide_container glide-1">
                    <div class="header">
                        <a href="/library">
                            <span>
                                {{ __('layout.library') }}
                                <i class="fas fa-photo-video"></i>
                            </span>
                        </a>
                        <div class="slider-navigation">
                            <!-- Next and Previous Buttons -->
                            <button class="glide__arrow glide__arrow--prev" data-glide-dir="<"><i class="fas fa-chevron-left"></i></button>
                            <button class="glide__arrow glide__arrow--next" data-glide-dir=">"><i class="fas fa-chevron-right"></i></button>
                        </div>
                    </div>
                    <div class="glide">
                        <div class="glide__track" data-glide-el="track">
                        <ul class="glide__slides">
                                @foreach ($libraries as $lib)
                                    <li class="glide__slide">
                                        <a href="/library/{{$lib->id}}">
                                            <div class="image-container">
                                                @if($lib->thumbnail)
                                                    <img src="{{ asset('storage/'. $lib->thumbnail) }}" alt="">
                                                @else
                                                    <div class="no-image">
                                                        <i class="fal fa-image"></i>
                                                    </div>
                                                @endif
                                                <div class="overlay">
                                                    {{ substr($lib->body, 0,300) }}
                                                    <p class="read-more">{{ __('layout.read_more') }}</p>
                                                </div>
                                            </div>
                                            <div class="meta">
                                                <p class="title">{{ $lib->title }}</p>
                                                <p class="time">
                                                    <i class="fal fa-clock"></i>
                                                    {{ $lib->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                        </ul>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.2.0/glide.min.js" integrity="sha512-IkLiryZhI6G4pnA3bBZzYCT9Ewk87U4DGEOz+TnRD3MrKqaUitt+ssHgn2X/sxoM7FxCP/ROUp6wcxjH/GcI5Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</x-layout>

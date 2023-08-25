<x-layout title="Home">

    @if ($libraries->count() == 0 && $blogs->count() == 0)
        <div class="no_records">
            {{ __('layout.no_records')}}
        </div>
    @else
        <div class="sliders">
            <div class="glide_container glide-2">
                @if ($blogs->count() > 0)
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
                @endif
            </div>
            <div class="glide_container glide-1">
                @if($libraries->count() > 0)
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

                @endif
            </div>
        </div>
    @endif

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.2.0/glide.min.js" integrity="sha512-IkLiryZhI6G4pnA3bBZzYCT9Ewk87U4DGEOz+TnRD3MrKqaUitt+ssHgn2X/sxoM7FxCP/ROUp6wcxjH/GcI5Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function calculatePerView(glide) {
            const screenWidth = window.innerWidth;
            let perView = 4; // Default value

            // Adjust perView based on screen width
            if (screenWidth < 576) {
                perView = 1;
            } else if (screenWidth < 900) {
                perView = 2;
            } else if (screenWidth < 1300) {
                perView = 3;
            }

            if(document.querySelector(`${glide} .glide .glide__slides`).children.length < perView) {
                // perView = document.querySelector(`${glide} .glide .glide__slides`).children.length;
                for(let i =1; i < perView; i++){
                    let child = document.createElement('li');
                    child.classList.add('glide__slide');
                    document.querySelector(`${glide} .glide .glide__slides`).appendChild(child);
                    document.querySelector(`${glide} .glide__arrow--next`).style.display = 'none';
                    document.querySelector(`${glide} .glide__arrow--prev`).style.display = 'none';
                }
            }



            return perView;
        }
        document.addEventListener("DOMContentLoaded", function () {
            // render glide 1 if there is any childrens
            if (document.querySelector(".glide-1").children.length > 0) {
                const glide1 = new Glide(".glide-1 .glide", {
                    type: "carousel",
                    startAt: 0,

                    perView: calculatePerView('.glide-1'),
                    direction: document.dir,
                    // More options here...
                });

                glide1.on('mount.after', function() {
                    // Get the total number of slides
                    const totalSlides = glide1.settings.slideCount;

                    // Disable the next button when the last slide is reached
                    const nextButton = document.querySelector('.glide-1 .glide__arrow--next');
                    nextButton.disabled = glide1.index === totalSlides - 1;
                });

                glide1.mount();

                // Get the navigation buttons
                const prevButton = document.querySelector('.glide-1 .glide__arrow--prev');
                const nextButton = document.querySelector('.glide-1 .glide__arrow--next');

                // Add click event listeners to the buttons
                prevButton.addEventListener('click', function() {
                    glide1.go('<');
                });

                nextButton.addEventListener('click', function() {
                    glide1.go('>');
                });
            }
            if(document.querySelector(".glide-2").children.length > 0) {
                // Glide 2
                const glide2 = new Glide(".glide-2 .glide", {
                    type: "carousel",
                    startAt: 0,
                    perView: calculatePerView('.glide-2'),
                        direction: document.dir,
                    // More options here...
                });

                glide2.on('mount.after', function() {
                    // Get the total number of slides
                    const totalSlides = glide2.settings.slideCount;

                    // Disable the next button when the last slide is reached
                    const nextButton = document.querySelector('.glide-2 .glide__arrow--next');
                    nextButton.disabled = glide2.index === totalSlides - 1;
                });

                glide2.mount();

                // Get the navigation buttons
                const prevButton2 = document.querySelector('.glide-2 .glide__arrow--prev');
                const nextButton2 = document.querySelector('.glide-2 .glide__arrow--next');

                // Add click event listeners to the buttons
                prevButton2.addEventListener('click', function() {
                    glide2.go('<');
                });

                nextButton2.addEventListener('click', function() {
                    glide2.go('>');
                });
            }

        });
    </script>
    @endpush
</x-layout>

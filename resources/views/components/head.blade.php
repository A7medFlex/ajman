<div>
    <header>
        <div class="logo">
            <a href="/">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </a>
        </div>
        <div class="operations">
            <div class="searching">
                {{-- <span class="search">
                    <i class="fal fa-search"></i>
                </span> --}}
                <div class="all_search" style="{{ session('results') ? 'display:block;' : 'display:none;' }}">
                    <div class="logo_close">
                        <div class="logo">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo">
                        </div>
                        <i onclick="this.parentElement.parentElement.style.display = 'none';document.body.style.overflow = 'auto';" class="fal fa-times"></i>
                    </div>
                    <form action="/search" method="GET">
                        <input type="text" name="q" id="q" placeholder="{{ __('layout.search_word') }}" value="{{ session('query') }}">
                        <button type="submit">{{ __('layout.search') }}</button>
                    </form>
                    @if(session('results'))
                        <div class="search_result">
                            <p class="count">
                                {{ __('layout.we_found',['count' => session('results')->count(), 'word' => session("query")] ) }}
                            <div class="grid">
                                @foreach (session('results') as $c)
                                    <li class="grid-item">
                                        <a href="{{ get_class($c) === "App\Models\Library" ? '/library/' . $c->id : '/blog/' . $c->id }}">
                                            <div class="image-container">
                                                @if($c->thumbnail)
                                                    <img src="{{ asset('storage/'. $c->thumbnail) }}" alt="">
                                                @else
                                                    <div class="no-image">
                                                        <i class="fal fa-image"></i>
                                                    </div>
                                                @endif
                                                <div class="overlay">
                                                    {{ substr($c->body, 0,300) }}
                                                    <p class="read-more">{{ __('layout.read_more') }}</p>
                                                </div>
                                            </div>
                                            <div class="meta">
                                                <p class="title">{{ $c->title }}</p>
                                                <p class="time">
                                                    <i class="fal fa-clock"></i>
                                                    {{ $c->created_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="collection">
                <div class="languages">
                    @if(app()->getLocale() === 'en')
                        <a href="/lang/change/ar">AR</a>
                    @else
                        <a href="/lang/change/en">EN</a>
                    @endif
                </div>
                {{-- <div class="searching">
                    <span class="search">
                        <i class="fal fa-search"></i>
                    </span>
                    <div class="all_search" style="{{ session('results') ? 'display:block;' : 'display:none;' }}">
                        <div class="logo_close">
                            <div class="logo">
                                <img src="{{ asset('images/logo.png') }}" alt="Logo">
                            </div>
                            <i onclick="this.parentElement.parentElement.style.display = 'none';" class="fal fa-times"></i>
                        </div>
                        <form action="/search" method="GET">
                            <input type="text" name="q" id="q" placeholder="{{ __('layout.search_word') }}" value="{{ session('query') }}">
                            <button type="submit">{{ __('layout.search') }}</button>
                        </form>
                        @if(session('results'))
                            <div class="search_result">
                                <p class="count">
                                    {{ __('layout.we_found',['count' => session('results')->count(), 'word' => session("query")] ) }}
                                <div class="grid">
                                    @foreach (session('results') as $c)
                                        <li class="grid-item">
                                            <a href="{{ get_class($c) === "App\Models\Library" ? '/library/' . $c->id : '/blog/' . $c->id }}">
                                                <div class="image-container">
                                                    @if($c->thumbnail)
                                                        <img src="{{ asset('storage/'. $c->thumbnail) }}" alt="">
                                                    @else
                                                        <div class="no-image">
                                                            <i class="fal fa-image"></i>
                                                        </div>
                                                    @endif
                                                    <div class="overlay">
                                                        {{ substr($c->body, 0,300) }}
                                                        <p class="read-more">{{ __('layout.read_more') }}</p>
                                                    </div>
                                                </div>
                                                <div class="meta">
                                                    <p class="title">{{ $c->title }}</p>
                                                    <p class="time">
                                                        <i class="fal fa-clock"></i>
                                                        {{ $c->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div> --}}
                <div class="chat">
                    <span class="chat" onclick="this.nextElementSibling.classList.toggle('active')">
                        <i class="fal fa-comment-alt"></i>
                    </span>
                    <div class="all_chats">
                        <a href="/chats" class="previous_chats">{{ __('layout.see_all_chats') }}</a>
                        <div class="create_chat_form">
                            <form class="add_chat" action="/chats" method="POST">
                                @csrf
                                <div class="form-group required propagated">
                                    <label class="propagated" for="title">{{ __('layout.title') }}</label>
                                    <input class="propagated" type="text" name="title" id="title" value="{{ old("title") }}" required>
                                    @error('title')
                                        <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="form-group optional flex-1 propagated">
                                    <label class="propagated" for="description">{{ __('layout.description') }}</label>
                                    <textarea class="propagated" name="description" id="description" cols="30" rows="10">{{ old('description') }}</textarea>
                                </div>
                                <div class="form-group propagated">
                                    <button class="propagated" type="submit">{{ __('layout.create_chat') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <a href="/events" class="events">
                    <i class="fal fa-calendar-alt"></i>
                </a>
            </div>
            <div class="notifications">
                <span class="notifications" onclick="this.nextElementSibling.classList.toggle('active')">
                    <i class="fal fa-bell"></i>
                    @if (auth()->user()->unreadNotifications()->count() > 0)
                        <span class="unread">{{ auth()->user()->unreadNotifications()->count() }}</span>
                    @endif
                </span>
                <div class="all_notifications">
                    @if (auth()->user()->unreadNotifications()->count() > 0)
                        <form action="/notifications" class="set_all_read propagated_notifications" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="propagated_notifications">{{ __('layout.set_all_read') }}</button>
                        </form>
                        <div class="display_notifications propagated_notifications">
                            @foreach (auth()->user()->unreadNotifications as $notification)
                                <div class="notification propagated_notifications">
                                    <div class="notification_image propagated_notifications">
                                        <i class="far fa-envelope-open-text propagated_notifications"></i>
                                    </div>
                                    <a href="/notifications/{{ $notification->id }}/{{ $notification->data['chat_id'] }}" class="propagated_notifications">
                                        <div class="notification_body propagated_notifications">
                                                <p class="notification_message propagated_notifications">
                                                    <span class="propagated_notifications">"{{ $notification->data['user'] }}"</span> {{ __('layout.message_added') }} <span>"{{ $notification->data['chat'] }}"</span>
                                                </p>
                                                <p class="notification_time propagated_notifications">{{ $notification->created_at->diffForHumans() }}</p>
                                        </div>
                                    </a>
                                    <div class="notification_operations propagated_notifications">
                                        <form action="/notifications/read/{{ $notification->id }}" method="POST" class="propagated_notifications">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="read propagated_notifications">
                                                <i class="fal fa-check propagated_notifications"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="no_records">
                            {{ __('layout.no_records')}}
                        </div>
                    @endif
                </div>
            </div>
            <div class="hamburger">
                <span class="hamburger">
                    <i class="fal fa-bars"></i>
                </span>
                <div class="all_hamburger">
                    <header>
                        <div class="logo">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo">
                        </div>
                        <div class="operations">
                            <span class="search">
                                <i class="fal fa-search"></i>
                            </span>
                            <i class="fal fa-times"></i>
                        </div>

                    </header>
                    <div class="links">
                        <ul>
                            <h3>
                                <i class="fas fa-user-cog"></i>
                                {{ __('layout.settings')}}
                            </h3>
                            @if(auth()->user()->is_admin)
                                <li><a href="/admin/users">
                                    <i class="fas fa-user-shield"></i>
                                    {{ __('layout.manage_users') }}
                                </a></li>
                                <li><a href="/admin/tags">
                                    <i class="fas fa-tags"></i>
                                    {{ __('layout.manage_tags') }}</a></li>
                            @endif
                            <li><a href="/users/{{ auth()->user()->id }}">
                                <i class="fas fa-user"></i>
                                {{ __('layout.account') }}</a>
                            </li>
                            <li><a href="/chats">
                                <i class="fas fa-comment-alt"></i>
                                {{ __('layout.chats') }}
                            </a></li>
                            <li><a href="/events">
                                <i class="fal fa-calendar-alt"></i>
                                {{ __('layout.events') }}
                            </a></li>
                            <li>
                                <form action="/logout" method="POST">
                                    @csrf
                                    <button type="submit">
                                        <i class="fas fa-sign-out-alt"></i>
                                        {{ __('layout.logout') }}
                                    </button>
                                </form>
                           </li>
                        </ul>
                        <ul>
                            <a href="/library">
                                <h3>
                                    <i class="fas fa-photo-video"></i>
                                    {{ __('layout.library') }}
                                </h3>
                            </a>
                            @foreach ($library_tags as $tag)
                                <li><a href="/library?tag={{ $tag->id }}">
                                    <i class="fas fa-tag"></i>
                                    {{ $tag->name }}
                                </a></li>
                            @endforeach
                        </ul>
                        <ul>
                            <a href="/blog">
                                <h3>
                                    <i class="fas fa-blog"></i>
                                    {{ __('layout.blog') }}
                                </h3>
                            </a>
                            @foreach ($blog_tags as $tag)
                                <li><a href="/blog?tag={{ $tag->id }}">
                                    <i class="fas fa-tag"></i>
                                    {{ $tag->name }}
                                </a></li>
                            @endforeach
                        </ul>
                        <div class="languages small_screens">
                            @if(app()->getLocale() === 'en')
                                <a href="/lang/change/ar">العربية</a>
                            @else
                                <a href="/lang/change/en">English</a>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </header>
</div>

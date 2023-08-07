<x-layout title="Account">
    <div class="profile">
        <div class="profile_image">
            @if($user->profile_image)
                <img src="{{ asset('storage/'. $user->profile_image) }}" alt="">
            @else
                <i class="fal fa-user"></i>
            @endif
        </div>
        <div class="name_email">
            <div class="name_role">
                <h3> {{ $user->name }}</h3>
            </div>
            <p>"{{ $user->job_name }}"</p>
            {{-- <div class="member_since">
                <i class="fal fa-calendar"></i>
                {{ __('layout.member_since') }} {{ $user->created_at->format('M Y') }}
            </div> --}}
        </div>
        @if(auth()->user()->id == $user->id)
            <a class="edit_acount" href="/users/{{ $user->id }}/edit">
                <i class="fal fa-edit"></i>
                <span>{{ __('layout.edit_profile')}}</span>
            </a>
        @endif
    </div>
    <div class="tags filter">
        <a href="/users/{{$user->id}}" class="tag {{ request('content') ? '' : 'active' }}">{{ __('layout.library') }}</a>
        <a href="/users/{{$user->id}}?content=blogs" class="tag {{ request('content') == 'blogs' ? 'active' : '' }}">{{ __('layout.blog') }}</a>
        <a href="/users/{{$user->id}}?content=chats" class="tag {{ request('content') == 'chats' ? 'active' : '' }}">{{ __('layout.chats') }}</a>
    </div>

    <div class="content">
        @if (request('content') == 'blogs' || ! request('content'))
            @if($content->count() > 0)
                <h3 class="gray_header">{{ request('content') ? __('layout.blog') : __('layout.library') }}</h3>
                <div class="grid">
                    @foreach ($content as $c)
                        <li class="grid-item">
                            <a href="{{ request('content') === 'blogs' ? '/blog/' . $c->id : '/library/' . $c->id }}">
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
                {{ $content->links() }}
            @else
                <p class="no_records">{{ __('layout.no_records') }}</p>
            @endif
        @else
            @if($content->count() > 0)
                <h3 class="gray_header">{{ __('layout.chats') }}</h3>
                <div class="chats-container">
                    @foreach ($content as $chat)
                        <a href="/chats/{{ $chat->id }}">
                            <div class="chat">
                                <div class="chat-details">
                                    <div class="title_status">
                                            <h3>{{ $chat->title }}</h3>
                                            <button class="status {{ $chat->is_open ? "active" : "closed"}}">{{ $chat->is_open ? __('layout.active') : __('layout.archived') }}</button>
                                    </div>


                                    <p class="time">
                                        <i class="fal fa-clock"></i>
                                        {{ $chat->created_at->diffForHumans() }}
                                    </p>
                                    <p class="body">{{ $chat->description }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                {{ $content->links() }}
            @else
                <p class="no_records">{{ __('layout.no_records') }}</p>
            @endif
        @endif
    </div>
</x-layout>

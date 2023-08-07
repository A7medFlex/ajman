<x-layout title="Chat">
    <div class="special_header">
        <span>
            <i class="fas fa-comments"></i>
            {{ __('layout.chats') }}
        </span>
        <a href="/chats/create" class="create_user">
            {{ __('layout.create_chat') }}
        </a>
    </div>
    <div class="chats-container">
        <div class="tags filter">
            <a href="/chats" class="tag {{ request('status') === '0' || request('status') === '1' ? '' : 'active' }}">{{ __('layout.all') }}</a>
            <a href="/chats?status=1" class="tag {{ request('status') === '1' ? 'active' : '' }}">{{ __('layout.active') }}</a>
            <a href="/chats?status=0" class="tag {{ request('status') === '0' ? 'active' : '' }}">{{ __('layout.archived') }}</a>
        </div>
        @if($chats->count() > 0)
            @foreach ($chats as $chat)
                <a href="/chats/{{ $chat->id }}">
                    <div class="chat">
                        <div class="chat-details">
                            <div class="title_status">
                                    <h3>{{ $chat->title }}</h3>
                                    <button class="status {{ $chat->is_open ? 'active' : 'closed' }}">{{ $chat->is_open ? __('layout.active') : __('layout.archived') }}</button>
                            </div>


                            <p class="time">
                                <i class="fal fa-clock"></i>
                                {{ $chat->created_at->diffForHumans() }}
                            </p>
                            <p class="body">{{ Str::substr($chat->description, 0, 100) }} ...</p>
                        </div>
                    </div>
                </a>
            @endforeach
            {{ $chats->links() }}
        @else
            <p class="no_records">
                {{ __('layout.no_records') }}
            </p>
        @endif
    </div>
</x-layout>

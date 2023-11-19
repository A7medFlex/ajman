<x-layout title="Chat">
    <div class="chat-details relative">
        <div class="chat-details">
            <div class="title_status">
                <h3>{{ $chat->title }}</h3>
            </div>
            <p class="author">{{ __('layout.created_by') }}: <a href="/users/{{ $chat->user_id }}">{{ $chat->user->name }}</a></p>

            <p class="time">
                <i class="fal fa-clock"></i>
                {{ $chat->created_at->diffForHumans() }}
            </p>

            <p class="body">{{ $chat->description }}</p>
        </div>
    </div>
</x-layout>

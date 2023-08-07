<x-layout title="Library">
    <div class="special_header">
        <span>
            <i class="fas fa-photo-video"></i>
            {{ __('layout.library') }}
        </span>
        <a href="/library/create" class="create_user">{{ __('layout.create_library') }}</a>
    </div>
    @if($libraries->count() > 0)
        <div class="tags filter">
                <a href="/library" class="tag {{ request('tag') ? '' : 'active' }}">{{ __('layout.all') }}</a>
            @foreach ($tags as $tag)
                <a href="/library?tag={{ $tag->id }}" class="tag {{ request('tag') == $tag->id ? 'active' : '' }}">{{ $tag->name }}</a>
            @endforeach
        </div>
        <div class="grid">
            @foreach ($libraries as $lib)
                <li class="grid-item">
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
        </div>
        {{ $libraries->links() }}
    @else
        <p class="no_records">
            {{ __('layout.no_records') }}
        </p>
    @endif
</x-layout>

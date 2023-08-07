<x-layout title="Blog">
    <div class="special_header">
        <span>
            <i class="fas fa-blog"></i>
            {{ __('layout.blog') }}
        </span>
        <a href="/blog/create" class="create_user">
            {{ __('layout.create_blog') }}
        </a>
    </div>
    @if($blogs->count() > 0)
        <div class="tags filter">
            <a href="/blog" class="tag {{ request('tag') ? '' : 'active' }}">{{ __('layout.all') }}</a>
            @foreach ($tags as $tag)
                <a href="/blog?tag={{ $tag->id }}" class="tag {{ request('tag') == $tag->id ? 'active' : '' }}">{{ $tag->name }}</a>
            @endforeach
        </div>
        <div class="grid">
            @foreach ($blogs as $blog)
                <li class="grid-item">
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
        </div>
        {{ $blogs->links() }}
    @else
        <p class="no_records">
            {{ __('layout.no_records') }}
        </p>
    @endif
</x-layout>

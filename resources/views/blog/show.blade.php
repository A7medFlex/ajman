<x-layout title="Blog">

    <div class="show">
        <div class="left">
            @if($blog->thumbnail)
                <img src="{{ asset('storage/'. $blog->thumbnail) }}" alt="">
            @else
                <div class="no-image">
                    <i class="fal fa-image"></i>
                </div>
            @endif

            <div class="meta">
                <p class="author">{{ __('layout.created_by') }}: <a href="/users/{{ $blog->user_id }}">{{ $blog->user->name }}</a></p>
                <p class="time">
                    <i class="fal fa-clock"></i>
                    {{ $blog->created_at->diffForHumans() }}
                </p>
            </div>
            @if($blog->user_id === auth()->id())
                <div class="actions">
                    <a href="/blog/{{ $blog->id }}/edit" class="btn btn-primary">
                        <i class="fal fa-edit"></i>
                        {{ __('layout.edit') }}
                    </a>
                    <form action="/blog/{{ $blog->id }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">
                            <i class="fal fa-trash"></i>
                            {{ __('layout.delete') }}
                        </button>
                    </form>
                </div>
            @endif

        </div>
        <div class="right">
            <h2>{{ $blog->title }}</h2>
            <div class="tags">
                @foreach ($blog->tags as $tag)
                    <a href="/blog?tag={{ $tag->id }}">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
            @if($blog->attachments->count() > 0)
                <div class="attachments">
                    <p class="title">{{ __('layout.attachments') }}:</p>
                    <ul>
                        @foreach ($blog->attachments as $attachment)
                            <li>
                                <a href="{{ asset('storage/'. $attachment->path) }}" download="{{ $attachment->name }}">
                                    <i class="fal fa-file"></i>
                                    {{ $attachment->name }}
                                    <i class="fal fa-download"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <p class="body">
                {{ $blog->body }}
            </p>
        </div>
    </div>

</x-layout>

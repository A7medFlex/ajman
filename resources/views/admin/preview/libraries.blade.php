<x-layout title="Library">

    <div class="show">
        <div class="left">
            @if($library->thumbnail)
                <img src="{{ asset('storage/'. $library->thumbnail) }}" alt="">
            @else
                <div class="no-image">
                    <i class="fal fa-image"></i>
                </div>
            @endif

            <div class="meta">
                <p class="author">{{ __('layout.created_by') }}: <a href="/users/{{ $library->user_id }}">{{ $library->user->name }}</a></p>
                <p class="time">
                    <i class="fal fa-clock"></i>
                    {{ $library->created_at->diffForHumans() }}
                </p>
            </div>


        </div>
        <div class="right">
            <h2>{{ $library->title }}</h2>
            <div class="tags">
                @foreach ($library->tags as $tag)
                    <a href="/library?tag={{ $tag->id }}">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
            @if($library->attachments->count() > 0)
                <div class="attachments">
                    <p class="title">{{ __('layout.attachments') }}</p>
                    <ul>
                        @foreach ($library->attachments as $attachment)
                            <li>
                                <a href="{{ asset('storage/'. $attachment->path) }}" download="{{ $attachment->name }}">
                                    <i class="fal fa-file"></i>
                                    {{ str($attachment->name)->beforeLast('.') }}
                                    <i class="fal fa-download"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <p class="body">
                {{ $library->body }}
            </p>
        </div>
    </div>

</x-layout>

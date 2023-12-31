<x-layout title="Event">

    <div class="show">
        <div class="left">
            @if($event->thumbnail)
                <img src="{{ asset('storage/'. $event->thumbnail) }}" alt="">
            @else
                <div class="no-image">
                    <i class="fal fa-image"></i>
                </div>
            @endif

            <div class="meta">
                {{-- <p class="author">{{ __('layout.created_by') }}: <a href="/users/{{ $event->user_id }}">{{ $event->user->name }}</a></p> --}}
                <p class="time" style="color: white;font-size:18px;">
                    <i class="fal fa-clock"></i>
                    <span class="at">{{ __('layout.start_date') }}:</span>
                    {{  \Carbon\Carbon::parse($event->start)->format('M d Y')}}
                </p>
                <p class="time" style="color: white;font-size:18px;">
                    <i class="fal fa-clock"></i>
                    <span class="at">{{ __('layout.end_date') }}:</span>
                    {{ \Carbon\Carbon::parse($event->end)->format('M d Y') }}
                </p>
            </div>

        </div>
        <div class="right">
            <h2>{{ $event->title }}</h2>
            {{-- <div class="tags">
                @foreach ($event->tags as $tag)
                    <a href="/event?tag={{ $tag->id }}">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div> --}}
            @if($event->attachments->count() > 0)
                <div class="attachments">
                    <p class="title">{{ __('layout.attachments') }}:</p>
                    <ul>
                        @foreach ($event->attachments as $attachment)
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
                {{ $event->details }}
            </p>
        </div>
    </div>


</x-layout>

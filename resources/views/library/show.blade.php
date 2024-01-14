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

            @if($library->user_id === auth()->id() || auth()->user()->is_admin)
                <div class="actions">
                    <a href="/library/{{ $library->id }}/edit" class="btn btn-primary">
                        <i class="fal fa-edit"></i>
                        {{ __('layout.edit') }}
                    </a>
                    <form action="/libraries/{{ $library->id }}" method="POST">
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
                                    {{ $attachment->name }}
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

    <div class="likable">
        <form action="/libraries/{{$library->id}}/likes/toggle" method="post">
            @method('PATCH')
            @csrf
            <button type="submit" class="btn btn-link">
                <span class="like {{ $library->isLiked() ? 'active' : '' }}">
                    <i class="fal fa-thumbs-up"></i>
                    <span>({{ $library->likes()->count() }})</span>
                </span>
            </button>
        </form>
        <div class="comments_count">
            <i class="fal fa-comments-alt"></i>
            <span>({{ $library->comments()->count() }})</span>
        </div>
    </div>

    <div class="commentable">
        <form action="/library/comments" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $library->id }}">
            <div class="form-group flex-1">
                <label for="body">أضف تعليق</label>
                <textarea name="body" id="body" cols="30" rows="10">{{ old('body') }}</textarea>
                @error('body')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group flex-1">
                <button type="submit">أضف</button>
            </div>
        </form>
        <div class="comments" style="margin-top: 40px;">
            @foreach ($comments as $comment)
                <div class="comment">
                    <div class="head">
                        @if($comment->user->profile_image)
                            <img src="{{ asset('storage/'. $comment->user->profile_image) }}" alt="Profile Image">
                        @else
                            <i class="fal fa-user"></i>
                        @endif
                        <div class="meta">
                            <span class="name">{{ $comment->user->name }}</span>
                            <span class="job">"{{ $comment->user->job_name }}"</span>
                        </div>
                    </div>
                    <p class="body">
                        {{ $comment->body }}
                    </p>
                </div>
            @endforeach

            {{ $comments->links() }}
        </div>
    </div>

</x-layout>

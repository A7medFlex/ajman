<x-layout title="Create Library">
    <div class="special_header">
        <span>
            <i class="fal fa-plus"></i>
            {{ __('layout.create_chat') }}
        </span>
    </div>
    <div class="form-data">
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

</x-layout>

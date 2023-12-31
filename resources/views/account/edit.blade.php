<x-layout title="Update User">
    <div class="special_header">
        <span>
            <i class="fas fa-user-edit"></i>
            {{ __('layout.update_user')}}
        </span>
    </div>
    <div class="form-data">
        <form action="/users/{{ $user->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method("PATCH")
            <div class="profile-image-container form-group flex-1">
                @if($user->profile_image)
                    <div class="profile-image-circle" style="display: none">
                        <input name="profile_image" type="file" id="imageUpload" accept="image/*" />
                        <label for="imageUpload">
                        <i class="fas fa-user-circle"></i>
                        </label>
                    </div>
                    <div id="imagePreview">
                        <img src="{{ asset("storage/". $user->profile_image) }}" alt="Profile Image" />
                    </div>
                @else
                    <div class="profile-image-circle">
                        <input name="profile_image" type="file" id="imageUpload" accept="image/*" />
                        <label for="imageUpload">
                        <i class="fas fa-user-circle"></i>
                        </label>
                    </div>
                    <div id="imagePreview"></div>
                @endif
                @error('profile_image')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group required">
                <label for="name">{{ __('layout.name') }}</label>
                <input type="text" name="name" id="name" value="{{ $user->name }}">
                @error('name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group required">
                <label for="email">{{ __('layout.email') }}</label>
                <input type="email" name="email" id="email" value="{{ $user->email }}">
                @error('email')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group required">
                <label for="job_name">{{ __('layout.job_name') }}</label>
                <input type="text" name="job_name" id="job_name" value="{{ $user->job_name }}">
                @error('job_name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group flex-1">
                <button type="submit">{{ __('layout.update_user') }}</button>
            </div>
        </form>
    </div>
</x-layout>

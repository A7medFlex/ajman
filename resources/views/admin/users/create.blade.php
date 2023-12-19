<x-layout title="Create User">
    <div class="special_header">
        <span>
            <i class="fal fa-plus"></i>
            {{ __('layout.create_user') }}
        </span>
    </div>

    <div class="form-data">
        <form action="/admin/users" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="profile-image-container form-group flex-1">
                <div class="profile-image-circle">
                  <input name="profile_image" type="file" id="imageUpload" accept="image/*" />
                  <label for="imageUpload">
                    <i class="fas fa-user-circle"></i>
                  </label>
                </div>
                <div id="imagePreview"></div>
                @error('profile_image')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group required">
                <label for="name">{{ __('layout.name') }}</label>
                <input type="text" name="name" id="name" value="{{ old("name") }}">
                @error('name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group required">
                <label for="email">{{ __('layout.email') }}</label>
                <input type="email" name="email" id="email" value="{{ old("email") }}">
                @error('email')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group required">
                <label for="password">{{ __('layout.password') }}</label>
                <input type="text" name="password" value="{{ old("password") }}"/>
                @error('password')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group required">
                <label for="mobile">رقم الهوية</label>
                <input type="string" name="idn" id="mobile" value="{{ old("idn") }}">
                @error('idn')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group optional">
                <label for="job_name">{{ __('layout.job_name') }}</label>
                <input type="string" name="job_name" id="job_name" value="{{ old("job_name", 'موظف') }}">
                @error('job_name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>


            <div class="form-group flex-1">
                <button type="submit">{{ __('layout.create_user') }}</button>
            </div>
        </form>
    </div>
</x-layout>


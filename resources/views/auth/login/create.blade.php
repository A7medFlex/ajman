<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.15.3/css/pro.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    @if(app()->getLocale() === 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,900;1,200&display=swap" rel="stylesheet">
    @endif
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <title>{{ __('layout.log_in') }}</title>
</head>
<body class="{{ app()->getLocale() === 'ar' ? 'ar' : '' }}">
    @if (session('success'))
        <p class="success popup">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </p>
    @endif
    @if (session('failed'))
        <p class="error popup">
            <i class="fas fa-wrong-circle"></i>
            {{ session('failed') }}
        </p>
    @endif
    <header>
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>
        <div class="operations">
            <div class="languages">
                @if(app()->getLocale() === 'en')
                    <a href="/lang/change/ar">AR</a>
                @else
                    <a href="/lang/change/en">EN</a>
                @endif
            </div>
        </div>
    </header>


    <form action="/login/email" method="post">
        @csrf
        <div class="form">
            <div class="container">
                <div class="form-group required">
                    <label for="email">{{ __('layout.email') }}</label>
                    <input type="email" name="email" value="{{ old("email") }}"/>
                    @error('email')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit">{{ __('layout.log_in') }}</button>
            </div>
        </div>
    </form>
</body>
</html>

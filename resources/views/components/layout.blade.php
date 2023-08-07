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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.2.0/css/glide.core.css" integrity="sha512-ShLuspGzRsTiMlQ2Rg0e+atjy/gVQr3oYKnKmQkHQ6sxcnDAEOtOaPz2rRmeygV2CtnwUawDyHkGgH4zUbP3Hw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @stack('styles')
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <title>{{ $title }}</title>
</head>
<body class="{{ app()->getLocale() === 'ar' ? 'ar' : '' }}">

    @if (session('success'))
        <p class="success popup">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </p>
    @endif

    <x-head />

    <div class="notify">
        <audio id="notification-sound">
            <source src="/sounds/notification.mp3" type="audio/mpeg">
         </audio>

         <button id="play-sound-btn">Play Sound</button>
    </div>


    <div class="container">
        {{ $slot }}
    </div>

    @stack('scripts')
</body>
</html>

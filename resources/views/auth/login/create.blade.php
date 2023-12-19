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
    {{-- @vite(['resources/scss/app.scss', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="/build/assets/app-1e3d105b.css">
    <script type="module" src="/build/assets/app-86ccaf00.js"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>

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
        <p class="error popup" style="color: black;">
            <i class="fas fa-wrong-circle"></i>
            {{ session('failed') }}
        </p>
    @endif
    <header>
        <div class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>
        {{-- <div class="operations">
            <div class="languages">
                @if(app()->getLocale() === 'en')
                    <a href="/lang/change/ar">AR</a>
                @else
                    <a href="/lang/change/en">EN</a>
                @endif
            </div>
        </div> --}}
    </header>

    <form action="{{ request()->path() === 'admin/login' ? '/admin/login' :  '/login/email' }}" method="post" id="loginForm">
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
                <div class="form-group required password">
                    <label for="password">{{ __('layout.password') }}</label>
                    <input type="password" name="password" value="{{ old("password") }}"/>                        <i class="fa fa-eye" onclick="showPassword(this)"></i>
                        <script>
                            function showPassword(e){
                                let password = e.previousElementSibling
                                let icon = e
                                if(password.type == "password"){
                                    password.type = "text"
                                    icon.classList.remove("fa-eye")
                                    icon.classList.add("fa-eye-slash")
                                }else{
                                    password.type = "password"
                                    icon.classList.remove("fa-eye-slash")
                                    icon.classList.add("fa-eye")
                                }
                            }
                        </script>
                    @error('password')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
                {{-- <button
                    class="g-recaptcha"
                    data-sitekey="{{ config('services.recaptcha.site_key') }}"
                    data-callback='onSubmit'
                    data-action='submit'
                >
                    {{ __('layout.log_in') }}
                </button> --}}
                <a href="/forget-password" class="forget_password">نسيت كلمة المرور ؟</a>
                <button type="submit">تسجيل الدخول</button>
                <a class="special_button" href="/uaepass/login">
                    <img src="/images/uaepass.svg" alt="">
                </a>
            </div>
        </div>
    </form>

    <script>
        function onSubmit(token) {
          document.getElementById("loginForm").submit();
        }
    </script>
</body>
</html>

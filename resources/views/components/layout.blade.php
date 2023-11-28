<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.15.3/css/pro.min.css">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>

    @if(app()->getLocale() === 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    @else
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,900;1,200&display=swap" rel="stylesheet">
    @endif

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Glide.js/3.2.0/css/glide.core.css" integrity="sha512-ShLuspGzRsTiMlQ2Rg0e+atjy/gVQr3oYKnKmQkHQ6sxcnDAEOtOaPz2rRmeygV2CtnwUawDyHkGgH4zUbP3Hw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @stack('styles')
    {{-- @vite(['resources/scss/app.scss', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="/build/assets/app-1e3d105b.css">
    <script type="module" src="/build/assets/app-86ccaf00.js"></script>
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


    <div class="container">
        {{ $slot }}
    </div>

    <div class="gpt_button" onclick="this.nextElementSibling.classList.toggle('active')">
        <img src="/images/logowithoutname.png" alt="Avatar">
    </div>

    <div class="parentgpt" onclick="this.classList.toggle('active')">
        <div class="chatgpt">
            <div class="gpt_logo">
                <div class="logo">
                    <img src="/images/logowithoutname.png" alt="Avatar">
                    المساعد الذكي
                </div>
                <span class="delete_history">حذف المحاثة</span>
            </div>
            <div class="messages">
                <div class="right message" style="display: none;">
                    {{-- <img src="images/gpt.png" alt="Avatar">
                    <p>تحدث مع Chat-GPT الان</p> --}}
                </div>
              </div>
              <!-- End Chat -->

              <!-- Footer -->
              <div class="bottom">
                <div class="snippet" style="justify-content: center;display:none;margin-block:10px;" data-title="dot-flashing">
                    <div class="stage">
                      <div class="dot-flashing"></div>
                    </div>
                </div>
                <form class="gpt">
                  <input type="text" id="message" name="message" placeholder="إكتب رسالتك ..." autocomplete="off">
                  <button type="submit">
                    <i class="fal fa-paper-plane"></i>
                  </button>
                </form>
              </div>
        </div>
    </div>

    <x-foot />


    @stack('scripts')

    <script>

        // get the messages from local storage and show them in messages
        if(localStorage.getItem('messages')) {
            let messages = JSON.parse(localStorage.getItem('messages'));
            for(let i=0;i<messages.length;i++){
                if(i%2==0){
                    $(".messages > .message").last().after('<div class="right message">' +
                    '<img src="{{ auth()->user()->profile_image ? asset('storage/'. auth()->user()->profile_image) : '/images/avatar.png' }}" alt="Avatar">' +
                    '<p>' + messages[i] + '</p>' +
                    '</div>');
                }else{
                    $(".messages > .message").last().after('<div class="left message">' +
                    '<img src="/images/logowithoutname.png" alt="Avatar">' +
                    '<p>' + messages[i] + '</p>' +
                    '</div>');
                }
            }
        }

        document.querySelector('.delete_history').addEventListener('click', function() {
            localStorage.removeItem('messages');
            $(".messages > .message").remove();
            // insert the first message
            $(".messages").append('<div class="right message" style="display:none;"></div>');

        });


        $('.chatgpt').click(function(e) {
            e.stopPropagation();
        });

        $("form.gpt").submit(function (event) {
            event.preventDefault();

            //Stop empty messages
            if ($("form.gpt #message").val().trim() === '') {
            return;
            }

            //Disable form
            $("form.gpt #message").prop('disabled', true);
            $("form.gpt button").prop('disabled', true);

            //Populate sending message
            $(".messages > .message").last().after('<div class="right message">' +
                '<img src="{{ asset('storage/'. auth()->user()->profile_image) }}" alt="Avatar">' +
                '<p>' + $("form.gpt #message").val() + '</p>' +
                '</div>');

            $(".snippet").css("display", "flex");

            let messages = JSON.parse(localStorage.getItem('messages')) || [];
            messages.push($("form.gpt #message").val());


            $.ajax({
            url: "/chatgpt",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            },
            data: {
                "model": "gpt-3.5-turbo",
                "content": $("form.gpt #message").val()
            }
            })
            .done(function (res) {

                $(".snippet").hide();

            //Populate receiving message
            $(".messages > .message").last().after('<div class="left message">' +
                '<img src="/images/logowithoutname.png" alt="Avatar">' +
                '<p>' + res + '</p>' +
                '</div>');

            messages.push(res);
            localStorage.setItem('messages', JSON.stringify(messages));


            //Cleanup
            $("form.gpt #message").val('');
            $(".messages").scrollTop($(".messages").height());

            //Enable form
            $("form.gpt #message").prop('disabled', false);
            $("form button").prop('disabled', false);
            });
        });

        </script>
</body>
</html>

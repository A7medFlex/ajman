<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset='utf-8' />
  <meta name="Authorization" content="{{ $key }}">
  <link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale/ar.js'></script>
  <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.15.3/css/pro.min.css">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  @if(app()->getLocale() === 'ar')
      <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
  @else
      <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,900;1,200&display=swap" rel="stylesheet">
  @endif
  {{-- @vite(['resources/scss/app.scss', 'resources/js/app.js']) --}}
  <link rel="stylesheet" href="/build/assets/app-619bf963.css">
  <script type="module" src="/build/assets/app-86ccaf00.js"></script>
  <title>Events</title>
</head>
<body class="{{ app()->getLocale() === 'ar' ? 'ar' : '' }}">
    <x-head></x-head>
    <div class="special_header">
        <span>
            <i class="fas fa-calendar-edit"></i>
            الفعاليات
        </span>
        <a href="/events/create" class="create_user">{{ __('layout.create_event') }}</a>
    </div>
    <div id='calendar'></div>

    <x-foot></x-foot>

  <script>
    $(document).ready(function() {
      function fetchEventsForMonth(year, month) {
        $.ajax({
          url: '/api/events',
          type: 'GET',
          dataType: 'json',
          headers: {
                'Authorization': $('meta[name="Authorization"]').attr('content')
            },
          data: {
            month: month,
            year: year
          },
          success: function(response) {
            $('#calendar').fullCalendar('removeEvents');
            $('#calendar').fullCalendar('addEventSource', response);
          },
          error: function(error) {
            console.error('Error fetching events: ', error);
          }
        });
      }

      $('#calendar').fullCalendar({
        defaultView: 'month',
        locale: 'en',
        header: {
          left: 'prev,next today',
          center: 'title',
          right: 'month,agendaWeek,agendaDay'
        },
        viewRender: function(view, element) {
          const currentYear = view.calendar.getDate().format('YYYY');
          const currentMonth = view.calendar.getDate().format('MM');
            fetchEventsForMonth(fixNumbers(currentYear), fixNumbers(currentMonth));
            // console.log();
        }
      });

    });
    var
        persianNumbers = [/۰/g, /۱/g, /۲/g, /۳/g, /۴/g, /۵/g, /۶/g, /۷/g, /۸/g, /۹/g],
        arabicNumbers  = [/٠/g, /١/g, /٢/g, /٣/g, /٤/g, /٥/g, /٦/g, /٧/g, /٨/g, /٩/g],
        fixNumbers = function (str)
        {
        if(typeof str === 'string')
        {
            for(var i=0; i<10; i++)
            {
            str = str.replace(persianNumbers[i], i).replace(arabicNumbers[i], i);
            }
        }
            return str;
        };
  </script>
</body>
</html>

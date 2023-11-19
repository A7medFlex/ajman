<x-mail::message>
# مرحبا {{ $username }}

قامت الإدارة بالموافقة علي طلبك لنشر {{ $type }} , إضغط علي الزر أدناه لمشاهدة التفاصيل

<x-mail::button :url="$url">
    مشاهدة التفاصيل
</x-mail::button>

شكرا ,<br>
برنامج سياسات عجمان

</x-mail::message>

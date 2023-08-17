<x-mail::message>

# Hi, there

A new chat created about {{ $title }} , click the button below to view the chat.

<x-mail::button :url="$url">
View Chat
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

<x-mail::message>
# Hi, {{ $name }}

Your account has been created. Please click the link below to login.

<x-mail::button :url="$url">
Log In
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

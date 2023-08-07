<x-mail::message>
# Hi, {{ $name }}

You are receiving this email because we received a login request for your account.
Please click the button below to login to your account.

<x-mail::button :url="$url">
Log In
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

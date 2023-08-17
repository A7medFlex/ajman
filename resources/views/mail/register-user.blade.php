<x-mail::message>
# Hi, {{ $name }}

Your account has been created. You can login with following credentials.
<p>Email: {{ $email }}</p>

<p>Password: {{ $password }}</p>

<x-mail::button :url="$url">
Log In
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

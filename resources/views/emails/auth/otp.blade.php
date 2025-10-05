@component('mail::message')
# Your verification code

Hi {{ $name }},

Use this 6-digit code to finish signing in:

@component('mail::panel')
## {{ $code }}
@endcomponent

This code expires in **{{ $ttl }} seconds**.

@component('mail::button', ['url' => $url])
Enter Code
@endcomponent

If you didn’t request this, you can ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

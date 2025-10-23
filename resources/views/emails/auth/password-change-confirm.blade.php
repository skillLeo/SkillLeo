@component('mail::message')
# Confirm Your Password Change

Hi {{ $user->name ?? $user->email }},

We received a request to change your password. Click the button below to confirm:

@component('mail::button', ['url' => $url])
Confirm Password Change
@endcomponent

If you didn’t request this, you can safely ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent

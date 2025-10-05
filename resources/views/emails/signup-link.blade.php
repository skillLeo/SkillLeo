@component('mail::message')
# Hi {{ $name }},

Tap the button below to confirm your email and create your SkillLeo account.

This link expires in 60 minutes.

@component('mail::button', ['url' => $url])
Create my account
@endcomponent

If the button doesn’t work, paste this URL into your browser:

{{ $url }}

Thanks,  
{{ config('app.name') }}
@endcomponent

@component('mail::message')
# Hi {{$user->name}},

Your password has been changed. Now you can login with your new password.

@component('mail::button', ['url' => route('login')])
Login
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

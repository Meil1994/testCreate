@component('mail::message')
Hello,

We understand it happens. Don't worry, we got you covered. Click the button below to reset your password.

@component('mail::button', ['url' => url('/user/update/password', ['token' => $user->remember_token])])
Reset your password
@endcomponent

Thanks, <br/>
Create Test
    
@endcomponent
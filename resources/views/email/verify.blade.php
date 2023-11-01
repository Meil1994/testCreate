@component('mail::message')
Hello,

Welcome to Test Create. To verify your email, please click the button below.

@component('mail::button', ['url' => url('/api/user/verify/email', ['id' => $user->id])])
Verify Your Email
@endcomponent

Thanks, <br/>
Create Test
    
@endcomponent
<!DOCTYPE html>
<html>
    <head>
        <title>Welcome Email</title>
    </head>

    <body>
        <h2>Dear {{$user->profile->name}},</h2>

        Welcome to the site <a href="{{ url('/') }}">Prognica Labs</a>
        <br/>
        Thank you for your interest in Prognica Labs. We will get back to you shortly.
        <br/>
        <a href="{{url('/verify_email', $user['remember_token'])}}">Click here</a> to verify your email or click on the below link
        <br/>
        <a href="{{url('/verify_email', $user['remember_token'])}}">{{url('/verify_email', $user['remember_token'])}}</a>
        <br/>
        <br/>
        Thanks!
    </body>

</html>
<!DOCTYPE html>
<html>
    <head>
        <title>Welcome Email</title>
    </head>

    <body>
        <h2>Dear {{$user->profile->name}},</h2>
        
        We've received reset password request from you. <a href="{{url('/reset_password', $user['remember_token'])}}">Click here</a> to reset your password.
        <br/>
        <br/>

        Thanks!
    </body>

</html>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
    </head>
    <body>
        {{ trans('users.password_reset_message', ['minutes' => $minutes]) }}
        <a href="{{ URL::to('users/password-reset') }}?token={{ $token }}">{{ URL::to('users/password-reset') }}?token={{ $token }}</a>
    </body>
</html>
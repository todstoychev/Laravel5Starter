<!DOCTYPE html>
<html>
    <head>
        <title></title>
    </head>
    <body>
        {{ trans('users.password_reset_message', ['minutes' => $minutes]) }}
        <a href="{{ url(\Illuminate\Support\Facades\App::getLocale() .  '/users/password-reset') }}?token={{ $token }}">{{ url(\Illuminate\Support\Facades\App::getLocale() .  '/users/password-reset') }}?token={{ $token }}</a>
    </body>
</html>
<!DOCTYPE html>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <p>
            {!! trans('users.registration_message', ['username' => $user_mail_data['username']]) !!}
        </p>
        <a href="{{ $user_mail_data['link'] }}">{{ $user_mail_data['link'] }}</a>
    </body>
</html>
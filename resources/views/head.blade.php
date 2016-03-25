<head>
        <!-- Favicon -->
        <link rel="icon" type="image/ico" href="{{ url(\App\Models\Settings::get('favicon')) }}" />

        <!-- Meta data -->
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title') | {{ App\Models\Settings::get('sitename_' . app()->getLocale()) }}</title>

        <link rel="stylesheet" href="{{ asset('bs/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('bs/css/bootstrap-theme.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/style.css') }}" />

        <script type="text/javascript" src="{{ asset('js/jquery.js') }}"></script>
        <script type="text/javascript" src="{{ asset('bs/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
    }
});
        </script>

        @yield('stylesheets')

        <!--[if lt IE 9]>
            <script src="{{ asset('js/modernizr.js') }}"></script>
            <script src="{{ asset('js/respond.js') }}"></script>
        <![endif]-->
    </head>
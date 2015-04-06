<!DOCTYPE html>
<html>
    <head>
        <!-- Favicon -->
        <link rel="icon" href="{{ URL::asset('favicon.ico') }}" />

        <!-- Meta data -->
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        <link rel="stylesheet" href="{{ URL::asset('bs/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ URL::asset('bs/css/bootstrap-theme.min.css') }}" />
        <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}" />

        <script type="text/javascript" src="{{ URL::asset('js/jquery.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('bs/js/bootstrap.min.js') }}"></script>
        <script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
    }
});
        </script>

        @yield('stylesheets')

        <!--[if lt IE 9]>
            <script src="{{ URL::asset('js/modernizr.js') }}"></script>
            <script src="{{ URL::asset('js/respond.js') }}"></script>
        <![endif]-->

        @yield('stylesheets')
    </head>
    <body>
        <header>
            @include('admin.navigation')
        </header>

        <div class="container">
            @include('vendor.flash.message')
        </div>

        <div class="container-fluid">
            <div class="col-sm-12">
                @yield('content')
            </div>
        </div>

        <footer>

        </footer>
        @yield('javascripts')
    </body>
</html>
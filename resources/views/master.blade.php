<!DOCTYPE html>
<html>
    @include('head')
    <body>
        <header>
            @include('navigation')
        </header>

        <div class="body">
            <div class="container">
                @include('vendor.flash.message')
                @yield('content')
            </div>
        </div>

        <footer class="navbar navbar-default" role="navigation">

        </footer>
        <script src="{{ URL::asset('js/main.js') }}"></script>
        @yield('javascripts')
    </body>
</html>
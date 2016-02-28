<!DOCTYPE html>
<html lang="{{ Session::has('locale') ? Session::get('locale') : Cache::get('settings')['fallback_locale'] }}">
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
        <script src="{{ asset('js/main.js') }}"></script>
        @yield('javascripts')
    </body>
</html>
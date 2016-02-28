<!DOCTYPE html>
<html lang="{{ \Illuminate\Support\Facades\App::getLocale() }}">
    @include('head')
    <body>
        <header>
            @include('admin.navigation')
        </header>

        <div class="container">
            @include('vendor.flash.message')
        </div>

        <div class="container-fluid">
            <div class="col-xs-12">
                @yield('content')
            </div>
        </div>

        <footer>

        </footer>
        @yield('javascripts')
    </body>
</html>
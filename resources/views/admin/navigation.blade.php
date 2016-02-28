<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url(\Illuminate\Support\Facades\App::getLocale() . '/') }}">
                <i class="glyphicon glyphicon-home"></i>
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <div class='navbar-left'>
                <ul class='nav navbar-nav'>
                    <!-- Settings -->
                    <li>
                        <a href="{{ url(\Illuminate\Support\Facades\App::getLocale() . '/admin/settings') }}">
                            {{ trans('settings.settings') }}
                        </a>
                    </li>

                    <!-- Users menu -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ trans('users.users') }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ url(\Illuminate\Support\Facades\App::getLocale() . '/admin/users/all?limit=10') }}">
                                    {{ trans('users.users') }}
                                </a>
                            </li>
                            <li>
                                <a href="{{ url(\Illuminate\Support\Facades\App::getLocale() . '/admin/roles/all?limit=10') }}">
                                    {{ trans('users.roles') }}
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Permissions menu -->
                    <li>
                        <a href="{{ url(\Illuminate\Support\Facades\App::getLocale() . '/admin/permissions/all') }}">
                            {{ trans('permissions.permissions') }}
                        </a>
                    </li>
                </ul>
            </div>
            <!-- /.navber-left -->

            <div class="navbar-right">
                @if (count(App\Models\Settings::getLocales()) > 1)
                    @include('change_locale')
                @endif
                @if(Auth::user())
                    @include('user_menu')
                @else
                    <ul class="nav navbar-nav">
                        <li>
                            <a href="{{ url(\Illuminate\Support\Facades\App::getLocale() . 'users/login') }}">
                                <i class="glyphicon glyphicon-log-in"></i> {{ trans('users.login') }}
                            </a>
                        </li>
                    </ul>
                @endif
            </div>
            <!-- /.navbar-right -->
        </div>
        <!-- /.navbar-collapse -->
    </div>
</nav>

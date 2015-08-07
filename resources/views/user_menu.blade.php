<ul class="nav navbar-nav">
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            @if (Auth::user()->avatar)
                <img src="{{ asset('uploads/images/avatar/small/' . Auth::user()->avatar) }}" alt="{{ trans('users.avatar') }}" />
            @else
                <i class="glyphicon glyphicon-user"></i>
            @endif
            {{ Auth::user()->username }} <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            <li>
                <a href="{{ URL::to('users/profile') }}">
                    <i class="glyphicon glyphicon-edit"></i> {{ trans('users.edit_profile') }}
                </a>
            </li>
            @if(Auth::user()->hasRole('admin'))
                <li class='divider'></li>
                <li>
                    <a href="{{ URL::to('admin') }}">
                        <i class="glyphicon glyphicon-cog"></i> {{ trans('admin.admin_panel') }}
                    </a>
                </li>
            @endif
            <li class="divider"></li>
            <li>
                <a href="{{ URL::to('users/logout') }}">
                    <i class="glyphicon glyphicon-log-out"></i> {{ trans('users.logout') }}
                </a>
            </li>
        </ul>
    </li>
</ul>
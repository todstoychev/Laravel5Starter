<ul class="nav navbar-nav">
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            {{ strtoupper(app()->getLocale()) }} <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
            @foreach (App\Models\Settings::getLocales() as $locale)
            <li>
                <a href="{{ url(app()->getLocale() . '/change-locale/' . $locale) }}">
                    {{ strtoupper($locale) }}
                </a>
            </li>
            @endforeach
        </ul>
    </li>
</ul>
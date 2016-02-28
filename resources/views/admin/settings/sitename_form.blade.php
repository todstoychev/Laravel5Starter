<form action="{{ url(\Illuminate\Support\Facades\App::getLocale() .  '/admin/settings/sitename') }}" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <input type="hidden" name="_method" value="PUT" />

    @if (count($locales) > 1)
    <div role="tabpanel">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            @foreach($locales as $locale)
            <li role="presentation" class="{{ $locales[0] == $locale ? 'active' : null }}">
                <a href="#{{ $locale }}" aria-controls="{{ $locale }}" role="tab" data-toggle="tab">
                    {{ strtoupper($locale) }}
                </a>
            </li>
            @endforeach
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <br />
            @foreach($locales as $locale)
            <div role="tabpanel" class="tab-pane {{ $locales[0] == $locale ? 'active' : null }}" id="{{ $locale }}">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">
                            <strong>{{ trans('settings.sitename') }}</strong>
                        </div>
                        <input type="text" name="sitename_{{ $locale }}" placeholder="{{ trans('settings.sitename') }}" value="{{ isset($settings['sitename_' . $locale]) ? $settings['sitename_' . $locale] : null }}" class="form-control" />
                    </div>
                </div>
            </div>
            @endforeach
        </div>

    </div>
    
    @else
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon">
                <strong>{{ trans('settings.sitename') }}</strong>
            </div>
            <input type="text" name="sitename_{{ $settings['fallback_locale'] }}" placeholder="{{ trans('settings.sitename') }}" value="{{ isset($settings['sitename_' . $settings['fallback_locale']]) ? $settings['sitename_' . $settings['fallback_locale']] : null }}" class="form-control" />
        </div>
    </div>
    @endif
    <hr />
    <div class="form-group">
        <input type="submit" value="{{ trans('temp.save') }}" class="btn btn-primary" />
    </div>
</form>
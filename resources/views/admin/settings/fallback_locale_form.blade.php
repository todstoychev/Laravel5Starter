<form action="{{ url(app()->getLocale() .  '/admin/settings/fallback-locale') }}" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <input type="hidden" name="_method" value="PUT" />
    
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon">
                <strong>{{ trans('settings.fallback_locale') }}</strong>
            </div>
            <input type="text" name="fallback_locale" class="form-control" placeholder="{{ trans('settings.fallback_locale') }}" value="{{ $settings['fallback_locale'] }}" />
        </div>
    </div>
    
    <div class="form-group">
        <input type="submit" value="{{ trans('temp.save') }}" class="btn btn-primary" />
    </div>
</form>
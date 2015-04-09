<form action="{{ URL::to('admin/settings/locales') }}" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <input type="hidden" name="_method" value="PUT" />
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon">
                <strong>{{ trans('settings.locales') }}</strong>
            </div>
            <input name="locales" type="text" class="form-control" value="{{ $settings['locales'] }}" />
        </div>
    </div>
    
    <div class="form-group">
        <input type="submit" value="{{ trans('temp.save') }}" class="btn btn-primary" />
    </div>
</form>
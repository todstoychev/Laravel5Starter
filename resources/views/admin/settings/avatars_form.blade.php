<form action="{{ URL::to('admin/settings/avatar') }}" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <input type="hidden" name="_method" value="PUT" />

    <div class="form-group">
        <div class="input-group">
            <input type="checkbox" name="use_avatars" {{ ($settings['use_avatars']) ? 'checked' : null }} />
            <label>{{ trans('settings.use_avatars') }}</label>
        </div>
    </div>

    <div class="form-group">
        <input type="submit" value="{{ trans('temp.save') }}" class="btn btn-primary" />
    </div>
</form>
<form action="{{ URL::to('admin/settings/contacts') }}" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <input type="hidden" name="_method" value="PUT" />

    <div class="form-group">
        <div class="input-group">
            <input type="checkbox" name="show_contacts_page" {{ ($settings['show_contacts_page']) ? 'checked' : null }} />
            <label>{{ trans('settings.show_contacts_page') }}</label>
        </div>
    </div>

    <div class="form-group">
        <input type="submit" value="{{ trans('temp.save') }}" class="btn btn-primary" />
    </div>
</form>
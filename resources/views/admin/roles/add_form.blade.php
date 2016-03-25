@if (isset($role))
<form action="{{ url(app()->getLocale() .  ''admin/roles/edit/' . $role->id) }}" method="POST">
    <input type="hidden" name="_method" value="PUT" />
@else
<form action="{{ url(app()->getLocale() .  '/admin/roles/add') }}" method="POST">
@endif
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon">
                <strong>*{{ trans('roles.role') }}</strong>
            </div>
            <input type="text" name="role" class="form-control" placeholder="{{ trans('roles.role') }}" value="{{ isset($role) ? $role->role : null }}" />
        </div>
    </div>

    <div class="form-group">
        <input type="submit" value="{{ trans('temp.save') }}" class="btn btn-primary" />
    </div>
</form>
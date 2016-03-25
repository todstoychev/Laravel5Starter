<form action="{{ url(app()->getLocale() .  '/users/change-password') }}" method="POST">
    <input name="_method" type="hidden" value="PUT" />
    <input name="_token" type="hidden" value="{{ csrf_token() }}" />

    <!-- Old Password -->
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon">
                <strong>
                    *{{ trans('users.old_password') }}
                </strong>
            </div>
            <input type="password" name="old_password" placeholder="{{ trans('users.old_password') }}" class="form-control" />
        </div>
    </div>
    
    <!-- New password -->
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon">
                <strong>
                    *{{ trans('users.new_password') }}
                </strong>
            </div>
            <input type="password" name="new_password" placeholder="{{ trans('users.new_password') }}" class="form-control" />
        </div>
    </div>
    
    <!-- New password again -->
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon">
                <strong>
                    *{{ trans('users.new_password_again') }}
                </strong>
            </div>
            <input type="password" name="new_password_confirmation" placeholder="{{ trans('users.new_password_again') }}" class="form-control" />
        </div>
    </div>
    
    <hr />
    
    <!-- Button -->
    <div class="form-group">
        <input type="submit" value="{{ trans('temp.save') }}" class="btn btn-primary" />
    </div>
</form>
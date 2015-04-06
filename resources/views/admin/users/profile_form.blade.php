<!-- Username -->
<div class="form-group">
    <div class="input-group">
        <div class="input-group-addon">
            <strong>*{{ trans('users.username') }}</strong>
        </div>
        <input name="username" type="text" placeholder="{{ trans('users.username') }}" class="form-control" value="{{ isset($user) ? $user->username : null }}" />
    </div>
</div>

<!-- Password -->
<div class="form-group">
    <div class="input-group">
        <div class="input-group-addon">
            <strong>*{{ trans('users.password') }}</strong>
        </div>
        <input type="password" name="password" class="form-control" placeholder="{{ trans('users.password') }}" />
    </div>
</div>

<!-- Password again -->
<div class="form-group">
    <div class="input-group">
        <div class="input-group-addon">
            <strong>*{{ trans('users.password_again') }}</strong>
        </div>
        <input type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('users.password_again') }}" />
    </div>
</div>

<!-- Email -->
<div class="form-group">
    <div class="input-group">
        <div class="input-group-addon">
            <strong>*{{ trans('users.email') }}</strong>
        </div>
        <input type="email" name="email" class="form-control" placeholder="{{ trans('users.email') }}" value="{{ isset($user) ? $user->email : null }}" />
    </div>
</div>
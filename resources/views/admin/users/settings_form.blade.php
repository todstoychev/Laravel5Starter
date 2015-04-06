<!-- Roles -->
<div class="form-group">
    <div class="input-group">
        <div class="input-group-addon">
            <strong>*{{ trans('users.roles') }}</strong>
        </div>
        <select name="roles[]" class="select2-no-search" multiple="true">
            @foreach($roles as $role)
            <option value="{{ $role->id }}" @if(isset($user) && $user->hasRole($role->role))selected="true"@endif>{{ $role->role }}</option>
            @endforeach
        </select>
    </div>
</div>

<!-- Active -->
<div class="form-group">
    <label>
        <input type="checkbox" name="active" @if(isset($user) && !$user->deleted_at)checked="true"@endif />
        {{ trans('users.active') }}
    </label>
</div>
<div class="form-group">
    @if (isset($user))
    <div class="col-sm-6">
        @if ($user->avatar)
        <img src="{{ URL::asset(Config::get('image_crop_resizer.uploads_path') . '/avatar/large/' . $user->avatar) }}" class="col-xs-12" />
        <hr />
        <a href="{{ URL::to('admin/users/delete-avatar/' . $user->id) }}" class="btn btn-danger btn-xs" title="{{ trans('users.delete_avatar') }}"><i class="glyphicon glyphicon-remove"></i></a>
        @else
        <img src="{{ URL::asset('images/no_image.png') }}" class="col-xs-12" />
        @endif
    </div>
    <div class="col-sm-6">
        <input type="file" name="avatar" />
    </div>
    @else
    <div class="col-xs-12">
        <input type="file" name="avatar" />
    </div>
    @endif
</div>
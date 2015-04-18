<div class="form-group">
    @if (isset($user))
    <div class="col-sm-6">
        @if ($user->avatar)
        <img src="{{ URL::asset(Config::get('image_crop_resizer.uploads_path') . '/avatar/large/' . $user->avatar) }}" class="col-xs-12" />
        <hr />
        <a href="#" title="{{ trans('temp.delete') }}" data-href="{{ URL::to('admin/users/delete-avatar/' . $user->id) }}" data-toggle="modal" data-target="#delete" class="btn btn-xs btn-danger delete-avatar">
            <i class="glyphicon glyphicon-remove"></i>
        </a>
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

<!-- Modal -->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('temp.close') }}</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('temp.delete') }}</h4>
            </div>
            <div class="modal-body">
                {{ trans('users.delete_avatar_message') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('temp.cancel') }}</button>
                <a href="#" class="btn btn-danger danger">{{ trans('temp.delete') }}</a>
            </div>
        </div>
    </div>
</div>
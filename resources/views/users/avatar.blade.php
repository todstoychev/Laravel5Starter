<div class="row">
    <div class="col-sm-6">
        @if (Auth::user()->avatar)
        <img src="{{ URL::asset(Config::get('image_crop_resizer.uploads_path') . '/avatar/large/' . Auth::user()->avatar) }}" class="col-xs-12" />
        <hr />
        <a href="{{ URL::to('users/delete-avatar') }}" class="btn btn-danger btn-xs" title="{{ trans('users.delete_avatar') }}"><i class="glyphicon glyphicon-remove"></i></a>
         @else
        <img src="{{ URL::asset('images/no_image.png') }}" class="col-xs-12" />
        @endif
    </div>
    <form action="{{ URL::to('users/change-avatar') }}" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div class="col-sm-6">
            <div class="form-group">
                <input type="file" name="avatar" />
            </div>
            
            <div class="form-group">
                <input type="submit" value="{{ trans('temp.save') }}" class="btn btn-primary" />
            </div>
        </div>
    </form>
</div>
<br />
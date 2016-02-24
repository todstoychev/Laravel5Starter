<div class="row">
    <div class="col-sm-6">
        @if (Auth::user()->avatar)
        <img src="{{ asset('uploads/images/avatar/large/' . Auth::user()->avatar) }}" class="col-xs-12" />
        <a href="#" title="{{ trans('temp.delete') }}" data-href="{{ URL::to('users/delete-avatar') }}" data-toggle="modal" data-target="#delete" class="btn btn-xs btn-danger delete-avatar">
            <i class="glyphicon glyphicon-remove"></i>
        </a>
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
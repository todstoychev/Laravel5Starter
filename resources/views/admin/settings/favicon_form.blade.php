<form action="{{ URL::to('admin/settings/favicon') }}" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="_method" value="PUT" />
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    
    @if($settings['favicon'])
    <div class="form-group">
        <img src="{{ URL::asset($settings['favicon']) }}" />
        <a href="{{ URL::to('admin/settings/delete-favicon') }}" title="{{ trans('settings.delete_favicon') }}" class="btn btn-xs btn-danger">
            <i class="glyphicon glyphicon-remove"></i>
        </a>
    </div>
    @endif
    
    <div class="form-group">
        <input type="file" name="favicon" />
    </div>
    
    <div class="form-group">
        <input type="submit" value="{{ trans('temp.save') }}" class="btn btn-primary" />
    </div>
</form>
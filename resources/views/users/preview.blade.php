<div class="row">
    <div class="col-sm-6">
        @if (Auth::user()->avatar)
        <img src="{{ URL::asset(Config::get('image_crop_resizer.uploads_path') . '/avatar/large/' . Auth::user()->avatar) }}" class="col-xs-12" />
        @else
        <img src="{{ URL::asset('images/no_image.png') }}" class="col-xs-12" />
        @endif
    </div>
    <div class="col-sm-6">
        <p><strong>{{ trans('users.username') }}: </strong>{{ Auth::user()->username }}</p>
        <p><strong>{{ trans('users.email') }}: </strong>{{ Auth::user()->email }}</p>
        <p><strong>{{ trans('users.registered_at') }}: </strong>{{ date('d M Y - H:i:s', strtotime(Auth::user()->created_at)) }}</p>
        <p><strong>{{ trans('users.updated_at') }}: </strong>{{ date('d M Y - H:i:s', strtotime(Auth::user()->updated_at)) }}</p>
        <p><strong>{{ trans('users.last_seen') }}: </strong>{{ date('d M Y - H:i:s', strtotime(Auth::user()->last_seen)) }}</p>
    </div>
</div>
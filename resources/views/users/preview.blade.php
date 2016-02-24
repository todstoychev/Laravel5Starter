<div class="row">
    @if(\App\Models\Settings::get('use_avatars'))
        <div class="col-sm-6">
            @if (Auth::user()->avatar)
                <img src="{{ asset('uploads/images/avatar/large/' . Auth::user()->avatar) }}"
                     class="col-xs-12"/>
            @else
                <img src="{{ asset('images/no_image.png') }}" class="col-xs-12"/>
            @endif
        </div>
    @endif
    <div class="{{ \App\Models\Settings::get('use_avatars') ? 'col-sm-6' : 'col-xs-12' }}">
        <p><strong>{{ trans('users.username') }}: </strong>{{ Auth::user()->username }}</p>

        <p><strong>{{ trans('users.email') }}: </strong>{{ Auth::user()->email }}</p>

        <p><strong>{{ trans('users.registered_at') }}
                : </strong>{{ date('d M Y - H:i:s', strtotime(Auth::user()->created_at)) }}</p>

        <p><strong>{{ trans('users.updated_at') }}
                : </strong>{{ date('d M Y - H:i:s', strtotime(Auth::user()->updated_at)) }}</p>

        <p><strong>{{ trans('users.last_seen') }}
                : </strong>{{ date('d M Y - H:i:s', strtotime(Cache::get('last_seen_' . Auth::user()->id))) }}</p>
    </div>
</div>
<br/>
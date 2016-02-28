@extends('admin.all')

@section('stylesheets')
    @parent
    @if(\App\Models\Settings::get('use_avatars'))
        <link rel="stylesheet" type="text/css" href="{{ asset('lightbox/css/lightbox.css') }}"/>
    @endif
@stop

@section('thead')
    <tr>
        @if(\App\Models\Settings::get('use_avatars'))
            <th>{{ trans('users.avatar') }}</th>
        @endif
        <th>{!! TableSorter::sort('Admin\AdminUsersController@getAll', trans('users.username'), 'users.username', $order, $limit) !!}</th>
        <th>{!! TableSorter::sort('Admin\AdminUsersController@getAll', trans('users.email'), 'users.email', $order, $limit) !!}</th>
        <th>{!! TableSorter::sort('Admin\AdminUsersController@getAll', trans('users.roles'), 'ur.role_id', $order, $limit) !!}</th>
        <th>{!! TableSorter::sort('Admin\AdminUsersController@getAll', trans('users.deactivated_at'), 'users.deleted_at', $order, $limit) !!}</th>
        <th>{!! TableSorter::sort('Admin\AdminUsersController@getAll', trans('users.registered_at'), 'users.created_at', $order, $limit) !!}</th>
        <th>{!! TableSorter::sort('Admin\AdminUsersController@getAll', trans('users.confirmed_at'), 'users.confirmed_at', $order, $limit) !!}</th>
        <th>{!! TableSorter::sort('Admin\AdminUsersController@getAll', trans('users.last_seen'), 'users.last_seen', $order, $limit) !!}</th>
        <th>{{ trans('temp.actions') }}</th>
    </tr>
@stop

@section('tbody')
    @foreach($results as $result)
        <tr>
            @if(\App\Models\Settings::get('use_avatars'))
                <td>
                    @if($result->avatar)
                        <a href="{{ asset('uploads/images/avatar/large/' .  $result->avatar) }}"
                           data-lightbox="{{ $result->id }}">
                            <img src="{{ asset('uploads/images/avatar/large/' . $result->avatar) }}"
                                 width="24" height="24"/>
                        </a>
                    @else
                        <i class="glyphicon glyphicon-user"></i>
                    @endif
                </td>
            @endif
            <td>{{ $result->username }}</td>
            <td>{{ $result->email }}</td>
            <td>{{ $result->getRoles() }}</td>
            <td>{{ $result->deleted_at ? date('d.m.Y - H:i:s', strtotime($result->deleted_at)) : '-' }}</td>
            <td>{{ date('d.m.Y - H:i:s', strtotime($result->created_at)) }}</td>
            <td>{{ $result->confirmed_at ? date('d.m.Y - H:i:s', strtotime($result->confirmed_at)) : '-' }}</td>
            <td>{{ $result->lastSeen() ? date('d.m.Y - H:i:s', strtotime($result->lastSeen())) : '-' }}</td>
            <td>
                <!-- Delete button -->
                <a href="#" title="{{ trans('temp.delete') }}"
                   data-href="{{ url(\Illuminate\Support\Facades\App::getLocale() . '/' . $uri . '/delete/' . $result->id) }}" data-toggle="modal" data-target="#delete"
                   class="btn btn-xs btn-danger">
                    <i class="glyphicon glyphicon-remove"></i>
                </a>

                <!-- Edit button -->
                <a href="{{ url(\Illuminate\Support\Facades\App::getLocale() . '/' . $uri . '/edit/' . $result->id) }}" title="{{ trans('temp.edit') }}"
                   class="btn btn-xs btn-warning">
                    <i class="glyphicon glyphicon-pencil"></i>
                </a>

                <!-- Disable -->
                <a href="{{ url(\Illuminate\Support\Facades\App::getLocale() . '/' . $uri . '/disable/' . $result->id) }}"
                   class="btn btn-xs btn-danger {{ ($result->deleted_at) ? 'disabled' : null }}"
                   title="{{ trans('temp.disable') }}">
                    <i class="glyphicon glyphicon-arrow-down"></i>
                </a>

                <!-- Restore -->
                <a href="{{ url(\Illuminate\Support\Facades\App::getLocale() . '/' . $uri . '/activate/' . $result->id) }}"
                   class="btn btn-xs btn-success {{ (!$result->deleted_at) ? 'disabled' : null }}"
                   title="{{ trans('users.activate') }}">
                    <i class="glyphicon glyphicon-arrow-up"></i>
                </a>
            </td>
        </tr>
    @endforeach
@stop

@section('javascripts')
    @parent
    @if(\App\Models\Settings::get('use_avatars'))
        <script src="{{ asset('lightbox/js/lightbox.min.js') }}"></script>
    @endif
@stop

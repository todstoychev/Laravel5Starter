@extends('admin.master')

@section('title')
    {{ $title }}
@stop

@section('content')
    <h1 class="page-header">{{ $title }}</h1>
    <div class="row">
        <div class="col-lg-6 pull-right">
            <form action="{{ url(app()->getLocale() . $uri . '/search') }}" method="POST" class="navbar-form pull-right" role="search">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                <div class="form-group">
                    <div class="input-group">
                        <input type="text" name="search" placeholder="{{ trans('temp.search') }}" class="form-control" value="{{ $search }}" />

                        <div class="input-group-addon" role="button" id="search">
                            <i class="glyphicon glyphicon-search"></i>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="table-responsive">
        <form action="{{ url(app()->getLocale() . '/admin/permissions/all') }}" method="POST">
            <input type="hidden" name="_method" value="PUT" />
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <table class="table table-condensed table-striped">
                <thead>
                <tr>
                    <th>{{ trans('permissions.uri') }}</th>
                    <th>{{ trans('permissions.action') }}</th>
                    @foreach($roles as $role)
                        <th>{{ $role->role }}</th>
                    @endforeach
                    <th>{{ trans('permissions.anonymous') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($actions as $action)
                    <tr>
                        <td>{{ $action->uri }}</td>
                        <td>{{ $action->action }}</td>
                        @foreach($action->permissions as $permission)
                            <td>
                                <input type="checkbox" name="permissions[{{ $permission->id }}]" {{ ($permission->allow) ? 'checked' : null }} />
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
            <hr />
            <input type="submit" value="{{ trans('temp.save') }}" class="btn btn-primary pull-right" />
        </form>
    </div>
@stop

@section('javascripts')
    <script type="text/javascript" src="{{ asset('select2/select2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/search.js') }}"></script>

@stop
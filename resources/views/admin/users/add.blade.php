@extends('admin.master')

@section('title')
{{ trans('users.add') }}
@stop

@section('stylesheets')
<link href="{{ asset('select2/select2.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('select2/select2-bootstrap.css') }}" rel="stylesheet" type="text/css" />
@stop

@section('content')
@if(isset($user))
<h1 class="page-header">{{ trans('users.edit') }}</h1>
<form action="{{ url(\Illuminate\Support\Facades\App::getLocale() .  '/admin/users/edit/' . $user->id) }}" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="_method" value="PUT" />
@else
<h1 class="page-header">{{ trans('users.add') }}</h1>
<form action="{{ url(\Illuminate\Support\Facades\App::getLocale() .  '/admin/users/add') }}" method="POST" enctype="multipart/form-data">
@endif
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <div class="col-sm-6 col-sm-offset-3">

        <div class="panel-group" id="accordion">
            <!-- Profile info -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#profile">
                            {{ trans('users.profile') }}
                            <i class="glyphicon glyphicon-chevron-down pull-right"></i>
                        </a>
                    </h4>
                </div>
                <div id="profile" class="panel-collapse collapse in">
                    <div class="panel-body">
                        @include('admin.users.profile_form')
                    </div>
                </div>
            </div>
            
            <!-- Avatar -->
            @if(\App\Models\Settings::get('use_avatars'))
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#avatar">
                            {{ trans('users.avatar') }}
                            <i class="glyphicon glyphicon-chevron-right pull-right"></i>
                        </a>
                    </h4>
                </div>
                <div id="avatar" class="panel-collapse collapse">
                    <div class="panel-body">
                        @include('admin.users.avatar_form')
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Settings -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#settings">
                            {{ trans('users.settings') }}
                            <i class="glyphicon glyphicon-chevron-right pull-right"></i>
                        </a>
                    </h4>
                </div>
                <div id="settings" class="panel-collapse collapse">
                    <div class="panel-body">
                        @include('admin.users.settings_form')
                    </div>
                </div>
            </div>
        </div>

        <hr />
        <div class="form-group">
            <input type="submit" name="submit" value="{{ trans('temp.save') }}" class="btn btn-primary" />
        </div>
    </div>
</form>
@stop

@section('javascripts')
<script src="{{ asset('js/accordion.js') }}"></script>
<script src="{{ asset('select2/select2.min.js') }}"></script>
<script>
$(document).ready(function () {
    $('select.select2-no-search').select2({
        minimumResultsForSearch: -1,
        width: '100%',
        placeholder: '{{ trans("users.select_role") }}'
    });
});
</script>
@stop
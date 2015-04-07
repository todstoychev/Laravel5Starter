@extends('admin.master')

@section('title')
    {{ trans('roles.edit_role') }}
@stop

@section('content')
<h1 class="page-header">{{ trans('roles.add_role') }}</h1>

<div class="col-sm-6 col-sm-offset-3">
    @include('admin.roles.add_form')
</div>
@stop
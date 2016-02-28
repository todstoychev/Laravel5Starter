@extends('master')

@section('title')
{{ trans('errors.oops_title') }}
@stop

@section('content')
<h1>{{ trans('errors.oops_title') }}</h1>
<div>
    {{ trans('errors.oops_message') }}
</div>
@stop
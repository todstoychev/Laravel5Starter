@extends('admin.master')

@section('title')
    {{ trans('slider.add_slider') }}
@stop

@section('content')
    <h1 class="page-header">{{ trans('slider.add_slider') }}</h1>

    <div class="col-sm-6 col-sm-offset-3">
        @include('admin.slider.form')
    </div>
@stop
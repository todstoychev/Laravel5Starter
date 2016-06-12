@extends('admin.master')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('lightbox/css/lightbox.css') }}"/>
    <link rel="stylesheet" href="{{ asset('select2/select2.css') }}"/>
    <link rel="stylesheet" href="{{ asset('select2/select2-bootstrap.css') }}"/>
@stop

@section('title')
    {{ trans('product.add_product') }}
@stop

@section('content')
    <h1 class="page-header">{{ trans('product.add_product') }}</h1>

    <div class="col-sm-6 col-sm-offset-3">
        @include('admin.product.form')
    </div>
@stop

@section('javascripts')
    <script src="{{ asset('lightbox/js/lightbox.min.js') }}"></script>
    <script src="{{ asset('select2/select2.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('select[name="currency"]').select2({
                placeholder: "@lang('product.select_currency')",
                width: '100%'
            })
        })
    </script>
@stop
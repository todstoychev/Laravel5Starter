@extends('admin.master')

@section('title')
{{ trans('temp.search_results') }}
@stop

@section('content')
<h1 class="page-header">{{ trans('temp.search_results') }}</h1>
<div class="row">
    <div class="col-lg-6">
        <a href="{{ URL::to($uri . '/add') }}" class="btn btn-primary" id="add"><i class="glyphicon glyphicon-plus-sign"></i> {{ trans('temp.add') }}</a>
    </div>
    <div class="col-lg-6">
        <form action="{{ URL::to($uri . '/search') }}" method="POST" class="navbar-form pull-right" role="search">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />

            <div class="form-group">
                <div class="input-group">
                    <input type="text" name="search" value="{{ isset($search) ? $search : null }}" placeholder="{{ trans('temp.search') }}" class="form-control" />
                    <div class="input-group-addon" role="button" id="search">
                        <i class="glyphicon glyphicon-search"></i>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<br />

<div class="row">
    <div class="col-lg-6">
        {{ trans('temp.total') }} <span class="label label-default">{{ count($results) }}</span> {{ trans('temp.results') }}
    </div>
</div>
<hr />
<div class="table-responsive">
    <table class="table table-condensed">
        <thead>
            @yield('thead')
        </thead>  
        <tbody>
            @yield('tbody')
        </tbody>
    </table>
</div>
<hr />
<div class="row">
    <div class="col-lg-6">
        {{ trans('temp.total') }} <span class="label label-default">{{ count($results) }}</span> {{ trans('temp.results') }}
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('temp.close') }}</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('temp.delete') }}</h4>
            </div>
            <div class="modal-body">
                {{ $delete_message }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('temp.cancel') }}</button>
                <a href="#" class="btn btn-danger danger">{{ trans('temp.delete') }}</a>
            </div>
        </div>
    </div>
</div>
@stop

@section('javascripts')
<script src="{{ URL::asset('js/select2.js') }}"></script>
<script>
    $(document).ready(function () {
        // Delete confirmation dialog
        $('#delete').on('show.bs.modal', function (e) {
            console.log($(e.relatedTarget).data('href'));
            $(this).find('.danger').attr('href', $(e.relatedTarget).data('href'));
        });

        // Submit the limit per page form
        $('select[name="limit"]').on('change', function () {
            var form = $(this).parent().parent('form');
            window.location = form.attr('action') + '/' + $(this).val() + '{{ $param ? "/" . $param : null }}' + '{{ $order ? "/" . $order : null }}';
        });

        $('i.glyphicon-search').on('click', function () {
            $(this).parentс()('form').submit();
        });
    });
</script>

@stop
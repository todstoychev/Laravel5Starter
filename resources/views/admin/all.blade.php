@extends('admin.master')

@section('title')
    {{ $title }}
@stop

@section('stylesheets')
    <link href="{{ URL::asset('select2/select2.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('select2/select2-bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@stop

@section('content')
    <h1 class="page-header">{{ $title }}</h1>
    <div class="row">
        <div class="col-lg-6">
            @section('add_button')
                <a href="{{ URL::to($uri . '/add') }}" class="btn btn-primary" id="add"><i
                            class="glyphicon glyphicon-plus-sign"></i> {{ trans('temp.add') }}</a>
            @stop
        </div>
        <div class="col-lg-6">
            <form action="{{ URL::to($uri . '/search') }}" method="POST" class="navbar-form pull-right" role="search">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                <div class="form-group">
                    <div class="input-group">
                        <input type="text" name="search" placeholder="{{ trans('temp.search') }}" class="form-control"/>

                        <div class="input-group-addon" role="button" id="search">
                            <i class="glyphicon glyphicon-search"></i>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <br/>

    <div class="row">
        <div class="col-lg-6">
            @include('admin.items_per_page')
        </div>
        <div class="col-lg-6 pull-right">
            <?php echo $results->render() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            {{ trans('temp.total') }}: <span
                    class="label label-default">{{ $results->total() }}</span> {{ trans('temp.results') }}
        </div>
    </div>
    <hr/>
    <div class="table-responsive">
        <table class="table table-condensed table-striped">
            <thead>
            @yield('thead')
            </thead>
            <tbody>
            @yield('tbody')
            </tbody>
        </table>
    </div>
    <hr/>
    <div class="row">
        <div class="col-lg-12">
            {{ trans('temp.total') }}: <span
                    class="label label-default">{{ $results->total() }}</span> {{ trans('temp.results') }}
        </div>
    </div>
    <div class="col-lg-6 pull-right">
        <?php echo $results->render() ?>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">{{ trans('temp.close') }}</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('temp.delete') }}</h4>
                </div>
                <div class="modal-body">
                    {{ $delete_message }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">{{ trans('temp.cancel') }}</button>
                    <a href="#" class="btn btn-danger danger">{{ trans('temp.delete') }}</a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascripts')
    <script type="text/javascript" src="{{ asset('select2/select2.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            // Delete confirmation dialog
            $('#delete').on('show.bs.modal', function (e) {
                $(this).find('.danger').attr('href', $(e.relatedTarget).data('href'));
            });

            // Select 2
            $('select.select2-no-search').select2({
                minimumResultsForSearch: -1
            });

            // Submit the limit per page form
            $('select[name="limit"]').on('change', function () {
                var form = $(this).parents('form');
                window.location = form.attr('action') + '?limit=' + $(this).val() + '&param={{ $param }}&order=' + '{{ $order }}';
            });
        });
    </script>
    <script type="text/javascript" src="{{ asset('js/search.js') }}"></script>
@stop
@extends('master')

@section('title')
    {{ trans('users.profile') }}
@stop

@section('content')
    <h1 class="page-header">{{ trans('users.profile') }}</h1>

    <div role="tabpanel" class="col-sm-6 col-sm-offset-3">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#preview" aria-controls="preview" role="tab" data-toggle="tab">
                    {{ trans('users.preview') }}
                </a>
            </li>
            @if(\App\Models\Settings::get('use_avatars'))
                <li role="presentation">
                    <a href="#avatar" aria-controls="avatar" role="tab" data-toggle="tab">
                        {{ trans('users.avatar') }}
                    </a>
                </li>
            @endif
            <li role="presentation">
                <a href="#password" aria-controls="password" role="tab" data-toggle="tab">
                    {{ trans('users.change_password') }}
                </a>
            </li>
            <li role="presentation">
                <a href="#email" aria-controls="email" role="tab" data-toggle="tab">
                    {{ trans('users.email') }}
                </a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="preview">
                <h2 class="page-header">{{ trans('users.profile') }}</h2>
                @include('users.preview')
            </div>
            @if(\App\Models\Settings::get('use_avatars'))
                <div role="tabpanel" class="tab-pane" id="avatar">
                    <h2 class="page-header">{{ trans('users.avatar') }}</h2>
                    @include('users.avatar')
                </div>
            @endif
            <div role="tabpanel" class="tab-pane" id="password">
                <h2 class="page-header">{{ trans('users.change_password') }}</h2>
                @include('users.password')
            </div>
            <div role="tabpanel" class="tab-pane" id="email">
                <h2 class="page-header">{{ trans('users.email') }}</h2>
                @include('users.email')
            </div>
        </div>
    </div>
@stop

@section('javascripts')
    <script>
        $(document).ready(function () {
            var profile_tab = "{{ Session::pull('profile_tab') }}";
            if (profile_tab) {
                $('div.tab-pane').removeClass('active');
                $('li[role="presentation"]').removeClass('active');
                $('div#' + profile_tab).addClass('active');
                $('a[aria-controls="' + profile_tab + '"]').parent('li').addClass('active');
            }

            // Delete confirmation dialog
            $('#delete').on('show.bs.modal', function (e) {
                $(this).find('.danger').attr('href', $(e.relatedTarget).data('href'));
            });
        });
    </script>
@stop

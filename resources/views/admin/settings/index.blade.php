@extends('admin.master')

@section('title')
    {{ trans('settings.settings') }}
@stop

@section('content')
    <h1 class="page-header">{{ trans('settings.settings') }}</h1>

    <div class="panel-group col-sm-6 pull-left" id="accordion1">

        <!-- Sitename -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion1" href="#sitename" class="collapsed">
                        {{ trans('settings.sitename') }}
                        <i class="glyphicon glyphicon-chevron-right pull-right"></i>
                    </a>
                </h4>
            </div>
            <div id="sitename" class="panel-collapse collapse">
                <div class="panel-body">
                    @include('admin.settings.sitename_form')
                </div>
            </div>
        </div>
        <!-- /Sitename -->

        <!-- Favicon -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion1" href="#favicon" class="collapsed">
                        {{ trans('settings.favicon') }}
                        <i class="glyphicon glyphicon-chevron-right pull-right"></i>
                    </a>
                </h4>
            </div>
            <div id="favicon" class="panel-collapse collapse">
                <div class="panel-body">
                    @include('admin.settings.favicon_form')
                </div>
            </div>
        </div>
        <!-- /Favicon -->

        <!-- Locales -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion1" href="#locales" class="collapsed">
                        {{ trans('settings.locales') }}
                        <i class="glyphicon glyphicon-chevron-right pull-right"></i>
                    </a>
                </h4>
            </div>
            <div id="locales" class="panel-collapse collapse">
                <div class="panel-body">
                    @include('admin.settings.locales_form')
                </div>
            </div>
        </div>
        <!-- /Locales -->
    </div>

    <div class="panel-group col-sm-6 pull-right" id="accordion2">
        <!-- Fallback locale -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#fallback-locale" class="collapsed">
                        {{ trans('settings.fallback_locale') }}
                        <i class="glyphicon glyphicon-chevron-right pull-right"></i>
                    </a>
                </h4>
            </div>
            <div id="fallback-locale" class="panel-collapse collapse">
                <div class="panel-body">
                    @include('admin.settings.fallback_locale_form')
                </div>
            </div>
        </div>
        <!-- /Fallback locale -->

        <!-- Contacts -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-parent="#accordion2" href="#contacts" class="collapsed">
                        {{ trans('settings.contacts') }}
                        <i class="glyphicon glyphicon-chevron-right pull-right"></i>
                    </a>
                </h4>
            </div>
            <div id="contacts" class="panel-collapse collapse">
                <div class="panel-body">
                    @include('admin.settings.contacts_form')
                </div>
            </div>
        </div>
        <!-- /Contacts -->
</div>
    @stop

@section('javascripts')
    <script>
        $(document).ready(function () {
            var settings_tab = "{{ $settings_tab }}";
            if (settings_tab) {
                $('a[href="#' + settings_tab + '"]').removeClass('collapsed');
                $('a[href="#' + settings_tab + '"]').attr('aria-expanded', true);
                $('a[href="#' + settings_tab + '"]').children('i').removeClass('glyphicon-chevron-right');
                $('a[href="#' + settings_tab + '"]').children('i').addClass('glyphicon-chevron-down');
                $('div#' + settings_tab).addClass('in');
                $('div#' + settings_tab).attr('aria-expanded', true);
            }
        });
    </script>
    <script src="{{ URL::asset('js/accordion.js') }}"></script>
@stop
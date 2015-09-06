@extends('admin.master')

@section('title')
{{ trans('settings.settings') }}
@stop

@section('content')
<h1 class="page-header">{{ trans('settings.settings') }}</h1>

<div class="panel-group col-sm-6 col-sm-offset-3" id="accordion">

    <!-- Sitename -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#sitename" class="collapsed">
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
    </div><!-- /Sitename -->
    
    <!-- Favicon -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#favicon" class="collapsed">
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
    </div><!-- /Favicon -->

    <!-- Locales -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#locales" class="collapsed">
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
    </div><!-- /Locales -->
    
    <!-- Locales -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#fallback-locale" class="collapsed">
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
    </div><!-- /Locales -->

    <!-- Contacts -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#contacts" class="collapsed">
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
    </div><!-- /Contacts -->
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
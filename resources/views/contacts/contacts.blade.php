@extends('master')

@section('title'){{ trans('contacts.contacts') }}@stop

@section('content')
    <h1 class="page-header">{{ trans('contacts.contacts') }}</h1>

    <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-2">
        <form action="{{ url(\Illuminate\Support\Facades\App::getLocale() . '/contacts/send-email') }}" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

            <!-- From field -->
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">
                        <strong>*{{  trans('contacts.from') }}</strong>
                    </div>
                    <input type="email" name="from" placeholder="your_email@domain.com"
                           value="{{ (Auth::user()) ? Auth::user()->email : null }}" class="form-control"/>
                </div>
            </div>

            <!-- Subject field -->
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">
                        <strong>*{{ trans('contacts.subject') }}</strong>
                    </div>
                    <input type="text" name="subject" placeholder="{{ trans('contacts.subject') }}" class="form-control" />
                </div>
            </div>

            <!-- Message field -->
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">
                        <strong>*{{ trans('contacts.message') }}</strong>
                    </div>
                    <textarea name="message" rows="10" placeholder="{{ trans('contacts.your_message') }}" class="form-control"></textarea>
                </div>
            </div>

            <!-- Send button -->
            <div class="form-group">
                <input type="submit" value="{{ trans('contacts.send') }}" class="btn btn-primary pull-right" />
            </div>
        </form>
    </div>
@stop

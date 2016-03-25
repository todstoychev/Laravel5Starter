@extends('master')

@section('title')
{{ trans('users.reset_password') }}
@stop

@section('content')
<h1 class="page-header">{{ trans('users.reset_password') }}</h1>

<div class="col-sm-6 col-sm-offset-3">
    <form action="{{ url(app()->getLocale() .  '/users/forgotten-password') }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        
        <!-- Email -->
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">
                    <strong>*{{ trans('users.email') }}</strong>
                </div>
                <input name="email" type="email" placeholder="your_email@domain.com" class="form-control" />
            </div>
        </div>
        
        <!-- Button -->
        <div class="form-group">
            <input type="submit" value="{{ trans('temp.send') }}" class="btn btn-primary" />
        </div>
    </form>
</div>
@stop
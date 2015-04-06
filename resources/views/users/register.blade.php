@extends('master')

@section('title')
{{ trans('users.register') }}
@stop

@section('content')
<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <h1 class="page-header">{{ trans('users.register') }}</h1>
    
    <form action="{{ URL::to('users/register') }}" method="POST">
        <input name="_token" type="hidden" value="{{ csrf_token() }}" />
        
        <!-- Username -->
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">
                    <strong>*{{ trans('users.username') }}</strong>
                </div>
                <input type="text" name="username" placeholder="{{ trans('users.username') }}" class="form-control" />
            </div>
        </div>
        
        <!-- Password -->
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">
                    <strong>*{{ trans('users.password') }}</strong>
                </div>
                <input type="password" name="password" placeholder="{{ trans('users.password') }}" class="form-control" />
            </div>
        </div>
        
        <!-- Password again -->
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">
                    <strong>*{{ trans('users.password_again') }}</strong>
                </div>
                <input type="password" name="password_confirmation" placeholder="{{ trans('users.password_again') }}" class="form-control" />
            </div>
        </div>
        
        <!-- Email -->
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">
                    <strong>*{{ trans('users.email') }}</strong>
                </div>
                <input type="email" name="email" placeholder="your_email@domain.com" class="form-control" />
            </div>
        </div>
        
        <!-- Button -->
        <div class="form-group">
            <input type="submit" value="{{ trans('users.register') }}" class="col-xs-12 btn btn-primary" />
        </div>
    </form>
</div>
@stop
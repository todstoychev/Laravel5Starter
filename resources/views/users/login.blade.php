@extends('master')

@section('title')
{{ trans('users.login') }}
@stop

@section('content')
<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <h1 class="page-header">{{ trans('users.login') }}</h1>
    
    <form action="{{ url(\Illuminate\Support\Facades\App::getLocale() .  '/users/login') }}" method="POST">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        
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
        
        <!-- Remmeber me -->
        <div class="form-group">
            <label>
                <input type="checkbox" name="remember_me" />
                {{ trans('users.remember_me') }}
            </label>
        </div>
        
        <!-- Forgotten password -->
        <div class="form-group text-center">
            <a href="{{ url(\Illuminate\Support\Facades\App::getLocale() .  '/users/forgotten-password') }}">
                {{ trans('users.forgotten_password') }}
            </a> | 
            <a href="{{ url(\Illuminate\Support\Facades\App::getLocale() .  '/users/register') }}">
                {{ trans('users.register') }}
            </a>
        </div>
        
        <!-- Button -->
        <div class="form-group">
            <input type="submit" value="{{ trans('users.login') }}" class="col-xs-12 btn btn-primary" />
        </div>
    </form>
</div>
@stop
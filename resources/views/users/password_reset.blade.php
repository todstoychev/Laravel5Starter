@extends('master')

@section('title')
{{ trans('users.reset_password') }}
@stop

@section('content')
<h1 class="page-header">{{ trans('users.reset_password') }}</h1>
<div class="col-sm-6 col-sm-offset-3">
    <form action="{{ url(\Illuminate\Support\Facades\App::getLocale() .  '/users/password-reset') }}?token={{ $token }}" method="POST">
        <input type="hidden" name="_method" value="PUT" />
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        
        <!-- Email -->
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">
                    <strong>*{{ trans('users.email') }}</strong>
                </div>
                <input type="email" name="email" class="form-control" placeholder="your_email@domain.com" />
            </div>
        </div>
        
        <!-- Password -->
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">
                    <strong>*{{ trans('users.password') }}</strong>
                </div>
                <input name="password" type="password" placeholder="{{ trans('users.password') }}" class="form-control" />
            </div>
        </div>
        
        <!-- Password again -->
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">
                    <strong>*{{ trans('users.password_again') }}</strong>
                </div>
                <input name="password_confirmation" type="password" placeholder="{{ trans('users.password_again') }}" class="form-control" />
            </div>
        </div>
        
        <!-- Button -->
        <div class="form-group">
            <input type="submit" value="{{ trans('temp.save') }}" class="btn btn-primary" />
        </div>
    </form>
</div>
@stop
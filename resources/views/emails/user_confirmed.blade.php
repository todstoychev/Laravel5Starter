@extends('emails.master')

@section('content')
{!! trans('users.account_confirmed_successfully', ['username' => $username]) !!}
@stop
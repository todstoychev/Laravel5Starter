@extends('emails.master')

@section('content')
<table>
    <tr>
        <th>Username: </th>
        <td>{{ $username }}</td>
    </tr>
    <tr>
        <th>Email: </th>
        <td>{{ $user_email }}</td>
    </tr>
    <tr>
        <th>Registered at: </th>
        <td>{{ date('d/m/Y - h:i:s', strtotime($created_at)) }}</td>
    </tr>
    <tr>
        <th>Confirmed at: </th>
        <td>{{ date('d/m/Y - h:i:s', strtotime($confirmed_at)) }}</td>
    </tr>
</table>
@stop
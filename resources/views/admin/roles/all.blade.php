@extends('admin.all')

@section('thead')
<tr>
    <th>
        {!! TableSorter::sort('Admin\AdminRolesController@getAll', trans('roles.role'), 'role', $order, $limit) !!}
    </th>
    <th>
        {{ trans('temp.actions') }}
    </th>
</tr>
@stop

@section('tbody')
@foreach($results as $result)
<tr>
    <td>{{ $result->role }}</td>
    <td>
        <!-- Delete button -->
        <a href="#" title="{{ trans('temp.delete') }}" data-href="{{ url(\Illuminate\Support\Facades\App::getLocale() . '/' . $uri . '/delete/' . $result->id) }}" data-toggle="modal" data-target="#delete" class="btn btn-xs btn-danger">
            <i class="glyphicon glyphicon-remove"></i>
        </a>

        <!-- Edit button -->
        <a href="{{ url(\Illuminate\Support\Facades\App::getLocale() . '/' . $uri . '/edit/' . $result->id) }}" title="{{ trans('temp.edit') }}" class="btn btn-xs btn-warning">
            <i class="glyphicon glyphicon-pencil"></i>
        </a>
    </td>
</tr>
@endforeach
@stop
@extends('admin.all')

@section('thead')
<tr>
    <th>
        {{ Lib\TableSorter::sort('Admin\AdminRolesController@getAll', trans('roles.role'), 'role', $order, $limit) }}
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
        <a href="#" title="{{ trans('temp.delete') }}" data-href="{{ $result->role != 'admin' ? URL::to($uri . '/delete/' . $result->id) : null }}" data-toggle="modal" data-target="#delete" class="btn btn-xs btn-danger {{ ($result->role == 'admin') ? 'disabled' : null }}">
            <i class="glyphicon glyphicon-remove"></i>
        </a>

        <!-- Edit button -->
        <a href="{{ URL::to($uri . '/edit/' . $result->id) }}" title="{{ trans('temp.edit') }}" class="btn btn-xs btn-warning">
            <i class="glyphicon glyphicon-pencil"></i>
        </a>
    </td>
</tr>
@endforeach
@stop
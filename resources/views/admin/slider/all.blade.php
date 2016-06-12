@extends('admin.all')

@section('stylesheets')
    @parent
    <link rel="stylesheet" type="text/css" href="{{ asset('lightbox/css/lightbox.css') }}"/>
@stop

@section('thead')
    <tr>
        <th>{!! TableSorter::sort('Admin\AdminSliderController@getAll', '#', 's.id', $order, $limit) !!}</th>
        <th>{{ trans('slider.image') }}</th>
        <th>{!! TableSorter::sort('Admin\AdminSliderController@getAll', trans('slider.title'), 'st.title', $order, $limit) !!}</th>
        <th>{!! TableSorter::sort('Admin\AdminSliderController@getAll', trans('slider.text'), 'st.text', $order, $limit) !!}</th>
        <th>{!! TableSorter::sort('Admin\AdminSliderController@getAll', trans('slider.link'), 's.link', $order, $limit) !!}</th>
        <th>{!! TableSorter::sort('Admin\AdminSliderController@getAll', trans('slider.created_at'), 's.created_at', $order, $limit) !!}</th>
        <th>{!! TableSorter::sort('Admin\AdminSliderController@getAll', trans('slider.updated_at'), 's.updated_at', $order, $limit) !!}</th>
        <th>{{ trans('temp.actions') }}</th>
    </tr>
@stop

@section('tbody')
    @foreach($results as $result)
        <tr>
            <td>{{ $result->id }}</td>
            <td>
                <a href="{{ asset('images/slider/normal/' .  $result->image_name) }}"
                   data-lightbox="{{ $result->id }}">
                    <img src="{{ asset('images/slider/small/' . $result->image_name) }}"/>
                </a>
            </td>
            <td>{{ (empty($result->title)) ? '-' : $result->title}}</td>
            <td>{{ (empty($result->text)) ? '-' : $result->text }}</td>
            <td>{{ (empty($result->link)) ? '-' : $result->link }}</td>
            <td>{{ $result->created_at->format('d.m.Y H:i:s') }}</td>
            <td>{{ $result->updated_at->format('d.m.Y H:i:s') }}</td>
            <td>
                <!-- Delete button -->
                <a href="#" title="{{ trans('temp.delete') }}"
                   data-href="{{ url(app()->getLocale() . '/' . $uri . '/delete/' . $result->id) }}"
                   data-toggle="modal"
                   data-target="#delete"
                   class="btn btn-xs btn-danger">
                    <i class="glyphicon glyphicon-remove"></i>
                </a>

                <!-- Edit button -->
                <a href="{{ url(app()->getLocale() . '/' . $uri . '/edit/' . $result->id) }}"
                   title="{{ trans('temp.edit') }}"
                   class="btn btn-xs btn-warning">
                    <i class="glyphicon glyphicon-pencil"></i>
                </a>
            </td>
        </tr>
    @endforeach
@stop

@section('javascripts')
    @parent
    <script src="{{ asset('lightbox/js/lightbox.min.js') }}"></script>
@stop

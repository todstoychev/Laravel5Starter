@extends('admin.search')

@section('stylesheets')
    @parent
    <link rel="stylesheet" type="text/css" href="{{ asset('lightbox/css/lightbox.css') }}"/>
@stop

@section('thead')
    <tr>
        <th>{!! TableSorter::sortSearch('Admin\AdminSliderController@getSearch', '#', $search, 's.id', $order) !!}</th>
        <th>{{ trans('slider.image') }}</th>
        <th>{!! TableSorter::sortSearch('Admin\AdminSliderController@getSearch', trans('slider.title'), $search, 'st.title', $order) !!}</th>
        <th>{!! TableSorter::sortSearch('Admin\AdminSliderController@getSearch', trans('slider.text'), $search, 'st.text', $order) !!}</th>
        <th>{!! TableSorter::sortSearch('Admin\AdminSliderController@getSearch', trans('slider.link'), $search, 's.link', $order) !!}</th>
        <th>{!! TableSorter::sortSearch('Admin\AdminProductController@getSearch', trans('slider.created_at'), $search, 's.created_at', $order) !!}</th>
        <th>{!! TableSorter::sortSearch('Admin\AdminProductController@getSearch', trans('slider.updated_at'), $search, 's.updated_at', $order) !!}</th>
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
            <td>{{ $carbon->createFromTimestamp(strtotime($result->created_at))->format('d.m.Y H:i:s') }}</td>
            <td>{{ $carbon->createFromTimestamp(strtotime($result->updated_at))->format('d.m.Y H:i:s') }}</td>
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

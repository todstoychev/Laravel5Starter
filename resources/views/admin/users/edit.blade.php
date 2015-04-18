@extends('admin.users.add')

@section('title')
{{ trans('users.edit') }}
@stop

@section('javascripts')
@parent
<script>
    $(document).ready(function () {
        // Delete confirmation dialog
        $('#delete').on('show.bs.modal', function (e) {
            $(this).find('.danger').attr('href', $(e.relatedTarget).data('href'));
        });
    });
</script>
@stop


@if (Session::has('flash_notification.message'))
@if (Session::has('flash_notification.overlay'))
@include('flash::modal', ['modalClass' => 'flash-modal', 'title' => Session::get('flash_notification.title'), 'body' => Session::get('flash_notification.message')])
@else
<div class="alert alert-{{ Session::get('flash_notification.level') }}">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    @if(Session::get('flash_notification.level') == 'info')
    <i class='glyphicon glyphicon-info-sign'></i>
    @elseif(Session::get('flash_notification.level') == 'success')
    <i class="glyphicon glyphicon-ok-sign"></i>
    @elseif(Session::get('flash_notification.level') == 'warning')
    <i class="glyphicon glyphicon-warning-sign"></i>
    @elseif(Session::get('flash_notification.level') == 'danger')
    <i class="glyphicon glyphicon-remove-sign"></i>
    @endif
    {{ Session::get('flash_notification.message') }}

    @if($errors)
    <ul>
        @foreach($errors as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    @endif
</div>
@endif
@endif

@if(count($errors) > 0)
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <i class="glyphicon glyphicon-remove-sign"></i> {{ trans('temp.error') }}
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


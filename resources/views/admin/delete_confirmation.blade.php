<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span><span class="sr-only">{{ trans('temp.close') }}</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('temp.delete') }}</h4>
            </div>
            <div class="modal-body">
                {{ $deleteMessage }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal">{{ trans('temp.cancel') }}</button>
                <a href="#" class="btn btn-danger danger">{{ trans('temp.delete') }}</a>
            </div>
        </div>
    </div>
</div>
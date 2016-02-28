<form action="{{ url(\Illuminate\Support\Facades\App::getLocale() .  '/users/change-email') }}" method='POST'>
    <input name="_method" type="hidden" value="PUT" />
    <input name="_token" type="hidden" value="{{ csrf_token() }}" />

    <!-- Email -->
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-addon">
                <strong>
                    *{{ trans('users.email') }}
                </strong>
            </div>
            <input type="email" name="email" placeholder="your_email@domain.com" value="{{ Auth::user()->email }}" class="form-control" />
        </div>
    </div>
    
    <!-- Button -->
    <div class="form-group">
        <input type="submit" value="{{ trans('temp.save') }}" class="btn btn-primary" />
    </div>
</form>
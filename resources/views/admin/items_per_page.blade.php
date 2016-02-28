<form action="{{ url(\Illuminate\Support\Facades\App::getLocale() . '/' . $uri . '/all') }}" method="POST">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <div class="form-group">
        <select name="limit" class="select2-no-search" id="limit" value="{{ $limit }}">
            @foreach(Config::get('view.items_per_page') as $value) 
                @if($limit == $value)
                <option value="{{ $value }}" selected="">{{ $value }}</option>
                @else
                <option value="{{ $value }}">{{ $value }}</option>
                @endif
            @endforeach
        </select>
    </div>
</form>
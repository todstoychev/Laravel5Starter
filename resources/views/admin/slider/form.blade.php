@if(isset($slider))
    <form action="{{ url(app()->getLocale() . '/admin/slider/edit', ['id' => $slider->id]) }}"
          method="POST"
          enctype="multipart/form-data"
    >
        <input type="hidden" name="_method" value="PUT"/>
        @else
            <form action="{{ url(app()->getLocale() . '/admin/slider/add')  }}"
                  method="POST"
                  enctype="multipart/form-data"
            >
            @endif
            {{ csrf_field() }}

            <!-- Image -->
                <h4 class="page-header">@lang('slider.image')</h4>
                @if(!isset($slider) || (isset($slider) && empty(isset($slider->image_name))))
                    <fieldset class="form-group">
                        <label>*@lang('slider.image')</label>
                        <input type="file" name="image"/>
                    </fieldset>
                @elseif(isset($slider))
                    <div>
                        <a href="{{ asset('images/slider/normal/' . $slider->image_name) }}"
                           data-lightbox="{{ $slider->image_name }}"
                        >
                            <img src="{{ asset('images/slider/medium/' . $slider->image_name) }}"
                                 alt="{{ $slider->image_name }}"
                                 class="col-xs-12"
                            />
                        </a>
                    </div>
            @endif

            <!-- Image name -->
                <fieldset class="form-group">
                    <label>*@lang('slider.image_name')</label>
                    <input type="text"
                           name="image_name"
                           placeholder="@lang('slider.image_name')"
                           class="form-control"
                           @if(isset($slider))
                           value="{{ preg_replace('/\.[a-z]{3,4}$/', '', $slider->image_name) }}"
                           @else
                           value="{{ old('image_name') }}"
                            @endif
                    />
                </fieldset>

                <!-- Slide info -->
                <h4 class="page-header">@lang('slider.info')</h4>
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    @foreach($locales as $locale)
                        <li role="presentation" @if($locale == $locales[0])class="active"@endif>
                            <a href="#{{ $locale }}" aria-controls="{{ $locale }}" role="tab" data-toggle="tab">
                                {{ strtoupper($locale) }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    @foreach($locales as $locale)
                        <div role="tabpanel"
                             @if($locale == $locales[0])
                             class="active tab-pane"
                             @else
                             class="tab-pane"
                             @endif
                             id="{{ $locale }}"
                        >
                            <br/>
                            <!-- Title -->
                            <fieldset class="form-group">
                                <label>@lang('slider.title')</label>
                                <input type="text"
                                       name="title[{{ $locale }}]"
                                       placeholder="@lang('slider.title')"
                                       class="form-control"
                                       @if(isset($slider))
                                       value="{{ $slider->translations->where('locale', $locale)->first()->title }}"
                                       @else
                                       value="{{ old('title') }}"
                                        @endif
                                />
                            </fieldset>

                            <!-- Text -->
                            <fieldset class="form-group">
                                <label>@lang('slider.text')</label>
                                <textarea name="text[{{ $locale }}]" placeholder="@lang('slider.text')"
                                          class="form-control">@if(isset($slider)){{ $slider->translations->where('locale', $locale)->first()->text }}@else{{ old('text') }}@endif</textarea>
                            </fieldset>
                        </div>
                    @endforeach
                </div>

                <!-- Link -->
                <fieldset class="form-group">
                    <label>@lang('slider.link')</label>
                    <input type="text"
                           name="link"
                           placeholder="@lang('slider.link')"
                           class="form-control"
                           @if(isset($slider))
                           value="{{ $slider->link }}"
                           @else
                           value="{{ old('link') }}"
                            @endif
                    />
                </fieldset>

                <!-- Save -->
                <fieldset class="form-group">
                    <input type="submit" value="@lang('temp.save')" class="btn btn-primary"/>
                </fieldset>
            </form>
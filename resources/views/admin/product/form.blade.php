@if (isset($product))
    <form action="{{ action('Admin\AdminProductController@putEdit', ['id' => $product->id]) }}"
          enctype="multipart/form-data"
          method="POST"
    >
        <input type="hidden" name="_method" value="PUT"/>
        @else
            <form method="POST" enctype="multipart/form-data">
                @endif
                {{ csrf_field() }}
                <h4 class="page-header">@lang('product.images')</h4>
                @if(isset($mainImage) && $mainImage !== null)
                    <div class="row">
                        <label>*@lang('product.main_image')</label>
                        <br/>
                        <div class="image-wrapper col-md-3 col-sm-4 col-xs-4">
                            <a href="{{ asset('images/product/normal/' . $mainImage->image_name) }}"
                               data-lightbox="{{ $mainImage->image_name }}"
                            >
                                <img src="{{ asset('images/product/medium/' . $mainImage->image_name) }}"
                                     alt="{{ $mainImage->image_name }}"
                                     class="col-xs-12"
                                />
                            </a>
                            <a href="#" class="btn btn-danger btn-xs remove-image-button"
                               title="@lang('product.remove_image')"
                               data-href="{{ url(app()->getLocale() . '/admin/product-image/delete/' . $mainImage->id) }}"
                               data-toggle="modal"
                               data-target="#delete"
                            >
                                <i class="glyphicon glyphicon-remove"></i>
                            </a>
                        </div>
                    </div>
                @endif

            <!-- Main image -->
                @if(!isset($mainImage) || $mainImage === null)
                    <fieldset class="form-group">
                        <label>@lang('product.main_image')</label>
                        <input type="file" name="main_image" value="{{ old('main_image') }}"/>
                    </fieldset>
            @endif

            <!-- Images fields -->
                <fieldset id="product-images">
                    <label>@lang('product.images')</label>
                    @if(isset($product))
                        <div class="row">
                            @foreach($product->productImages as $image)
                                @if(!$image->main_image)
                                    <div class="image-wrapper col-md-3 col-sm-4 col-xs-4">
                                        <a href="{{ asset('images/product/normal/' . $image->image_name) }}"
                                           data-lightbox="{{ $image->image_name }}"
                                        >
                                            <img src="{{ asset('images/product/medium/' . $image->image_name) }}"
                                                 alt="{{ $image->image_name }}"
                                                 class="col-xs-12"
                                            />
                                        </a>
                                        <a href="#" class="btn btn-danger btn-xs remove-image-button"
                                           title="@lang('product.remove_image')"
                                           data-href="{{ url(app()->getLocale() . '/admin/product-image/delete/' . $image->id) }}"
                                           data-toggle="modal"
                                           data-target="#delete"
                                        >
                                            <i class="glyphicon glyphicon-remove"></i>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    <div class="form-group">
                        <input type="file" name="product_images[]" multiple="multiple"
                               value="{{ old('product_images') }}"/>
                    </div>
                </fieldset>

                <hr/>

                <!-- Product info -->
                <h4 class="page-header">@lang('product.product_info')</h4>

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
                                <label>*@lang('product.title')</label>
                                <input type="text"
                                       name="title[{{ $locale }}]"
                                       class="form-control"
                                       placeholder="@lang('product.title')"
                                       @if(isset($product))
                                       value="{{ $product->productTranslations->where('locale', $locale)->first()->title }}"
                                       @else
                                       value="{{ old('title.' . $locale) }}"
                                        @endif
                                >
                            </fieldset>

                            <!-- Description -->
                            <fieldset class="form-group">
                                <label>*@lang('product.description')</label>
                                <textarea name="description[{{ $locale }}]"
                                          placeholder="@lang('product.description')"
                                          class="form-control"
                                >@if(isset($product)){{ $product->productTranslations->where('locale', $locale)->first()->description }}@else{{ old('title.' . $locale) }}@endif</textarea>
                            </fieldset>
                        </div>
                    @endforeach
                </div>

                <!-- Price -->
                <fieldset class="form-group">
                    <label>*@lang('product.price')</label>
                    <input type="text"
                           name="price"
                           placeholder="@lang('product.price')"
                           class="form-control"
                           @if(isset($product))
                           value="{{ $product->price }}"
                           @else
                           value="{{ old('price') }}"
                            @endif
                    />
                </fieldset>

                <!-- Currency -->
                <fieldset class="form-group">
                    <label>*@lang('product.currency')</label>
                    <select name="currency">
                        <option></option>
                        @foreach($currencies as $currency)
                            <option value="{{ $currency }}"
                                    @if($currency == old('curency') || (isset($product) && $product->currency == $currency))
                                    selected=""
                                    @endif
                            >{{ $currency }}</option>
                        @endforeach
                    </select>
                </fieldset>

                <!-- Quantity -->
                <fieldset class="form-group">
                    <label>*@lang('product.qty')</label>
                    <input type="number"
                           placeholder="@lang('product.qty')"
                           name="qty"
                           class="form-control"
                           @if(isset($product))
                           value="{{ $product->qty }}"
                           @else
                           value="{{ old('qty') }}"
                            @endif
                    />
                </fieldset>

                <!-- Save -->
                <fieldset class="form-group">
                    <input type="submit" value="@lang('temp.save')" class="btn btn-primary"/>
                </fieldset>
            </form>
@extends('user.layout')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/cropper.css') }}">
@endsection
@section('content')
    @php
        $type = request()->input('type');
    @endphp
    <div class="page-header">
        <h4 class="page-title">{{ __('Add Item') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="#">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Shop Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Products') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Items') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Add Item') }}</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Add Item') }}</div>

                    <button type="button" class="btn btn-primary btn-sm float-right ml-2 ai-field-btn"
                        id="aiGenerateItemSeoBtn">
                        <i class="fas fa-magic mr-1"></i> {{ __('Generate All Content') }}
                    </button>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('user.item.index') . '?language=' . request()->input('language') }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ __('Back') }}
                    </a>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-9 m-auto">
                            <div class="alert alert-danger pb-1 d-none" id="postErrors">
                                <ul></ul>
                            </div>
                            <div class="px-2">
                                <label for="" class="mb-2"><strong>{{ __('Slider Images') }} <span
                                            class="text-danger">**</span></strong></label>
                                <form action="{{ route('user.item.slider') }}" id="my-dropzone"
                                    enctype="multipart/form-data" class="dropzone create">
                                    <div class="dz-message">
                                        {{ __('Drag and drop files here to upload') }}
                                    </div>
                                    @csrf
                                    <div class="fallback">
                                    </div>
                                </form>
                                <div class="mt-2 d-flex" style="gap:10px;">
                                    <button type="button" class="btn btn-info btn-sm" data-ai-slider-open
                                        data-endpoint="{{ route('user.ai.generate.slider.images') }}"
                                        data-count-default="3" data-max-count="10" data-dropzone="#my-dropzone"
                                        data-hidden-wrap="#sliders" data-upload-endpoint="{{ route('user.item.slider') }}"
                                        data-remove-endpoint="{{ route('user.item.slider-remove') }}">
                                        <i class="fas fa-magic mr-1"></i> {{ __('Generate Slider Images') }}
                                    </button>
                                </div>

                                <p class="text-warning">
                                    <strong>{{ __('Recommended Image size : 800x800') }}</strong>
                                </p>
                                <p class="em text-danger mb-0" id="err_slider_images"></p>
                            </div>
                            <form id="itemForm" class="" action="{{ route('user.item.store') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="type" value="{{ request()->input('type') }}">
                                <input type="hidden" name="language" value={{ request()->input('language') }}>

                                <input type="hidden" name="ai_generated_image" id="ai_generated_image">

                                <div id="sliders"></div>

                                {{-- START: Featured Image --}}
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="col-12 mb-2 pl-0">
                                                <label for="image"><strong>{{ __('Thumbnail Image') }} <span
                                                            class="text-danger">**</span></strong></label>
                                            </div>
                                            <div class="col-md-12 showImage mb-3 pl-0 pr-0">
                                                <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..."
                                                    class="cropped-thumbnail-image">
                                            </div>
                                            <br>

                                            <div class="d-flex align-items-center" style="gap:10px;">
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#thumbnail-image-modal">
                                                    {{ __('Choose Image') }}
                                                </button>

                                                <button type="button" class="btn btn-info" data-ai-image-open
                                                    data-endpoint="{{ route('user.ai.generate.category.image') }}"
                                                    data-target=".showImage img.cropped-thumbnail-image"
                                                    data-hidden="#ai_generated_image" data-confirm-text="{{ __('Generate Image') }}"
                                                    id="aiCategoryImageModal">
                                                    <i class="fas fa-magic mr-1"></i> {{ __('Generate Image') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- END: Featured Image --}}

                                    @if ($type == 'physical')
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for="">{{ __('Stock') }}</label>
                                                <input type="number" value="0" id="productStock"
                                                    class="form-control" name="stock" min="0"
                                                    placeholder="{{ __('Enter Stock') }}">
                                                <p class="mb-0 text-warning">
                                                    {{ __('If the item has variations, then set the stocks in the variations page') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($type == 'digital')
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="">{{ __('Type') }} <span
                                                        class="text-danger">**</span></label>
                                                <select name="file_type" class="form-control" id="fileType">
                                                    <option value="upload" selected>{{ __('File Upload') }}</option>
                                                    <option value="link">{{ __('File Download Link') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div id="downloadFile" class="form-group">
                                                <label for="">{{ __('Downloadable File') }} <span
                                                        class="text-danger">**</span></label>
                                                <br>

                                                <input name="download_file" type="file" class="form-control">
                                                <p class="mb-0 text-warning">
                                                    {{ __('Only zip file is allowed.') }}</p>
                                            </div>
                                            <div id="downloadLink" class="form-group d-none">
                                                <label for="">{{ __('Downloadable Link') }} <span
                                                        class="text-danger">**</span></label>
                                                <input name="download_link" type="text" class="form-control">
                                            </div>
                                        </div>
                                    @endif

                                    @if ($type == 'physical')
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label for=""> {{ __('Product Sku') }} <span
                                                        class="text-danger">**</span></label>
                                                <input type="text" class="form-control" name="sku"
                                                    value="{{ rand(1000000, 9999999) }}"
                                                    placeholder="{{ __('Enter Product sku') }}">
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="">{{ __('Status') }} <span
                                                    class="text-danger">**</span></label>
                                            <select class="form-control" name="status">
                                                <option value="" selected disabled>
                                                    {{ __('Select Status') }}</option>
                                                <option value="1">{{ __('Show') }}</option>
                                                <option value="0">{{ __('Hide') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for=""> {{ __('Current Price') }}
                                                ({{ $currency->symbol }})
                                                <span class="text-danger">**</span></label>
                                            <input type="number" class="form-control" name="current_price"
                                                value="" step="any" min="0.01"
                                                placeholder="{{ __('Enter Current Price') }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="">{{ __('Previous Price') }}
                                                ({{ $currency->symbol }})</label>
                                            <input type="number" class="form-control" name="previous_price"
                                                value="" min="0.01" step="any"
                                                placeholder="{{ __('Enter Previous Price') }}">
                                        </div>
                                    </div>

                                    <input hidden id="subcatGetterForItem" value="{{ route('user.item.subcatGetter') }}">
                                    <div class="col-lg-4">
                                        <div class="form-group {{ $lang->rtl == 1 ? 'rtl text-right' : '' }}">
                                            <label>{{ __('Category') }} <span class="text-danger">**</span></label>
                                            <select data-code="{{ $lang->code }}" name="category"
                                                class="form-control getSubCategory">
                                                <option value="" disabled selected>
                                                    {{ __('Select Category') }}
                                                </option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">
                                                        {{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group {{ $lang->rtl == 1 ? 'rtl text-right' : '' }}">
                                            <label>{{ __('Subcategory') }}</label>
                                            <select data-code="{{ $lang->code }}" name="subcategory"
                                                id="{{ $lang->code }}_subcategory" class="form-control">
                                                <option value="" selected>
                                                    {{ __('Select Subcategory') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    @php
                                        $allow_background_color = ['pet'];
                                    @endphp
                                    @if (in_array($userBs->theme, $allow_background_color))
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label>{{ __('Background Color') }}</label>
                                                <input type="text" class="form-control jscolor"
                                                    name="background_color">
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div id="accordion" class="mt-3">
                                    @foreach ($languages as $language)
                                        <div class="version">
                                            <div class="version-header" id="heading{{ $language->id }}">
                                                <h5 class="mb-0">
                                                    <button type="button" class="btn btn-link" data-toggle="collapse"
                                                        data-target="#collapse{{ $language->id }}"
                                                        aria-expanded="{{ $language->is_default == 1 ? 'true' : 'false' }}"
                                                        aria-controls="collapse{{ $language->id }}">
                                                        {{ $language->name . ' ' . __('Language') }}
                                                        {{ $language->is_default == 1 ? __('(Default)') : '' }}
                                                    </button>
                                                </h5>
                                            </div>
                                            <div id="collapse{{ $language->id }}"
                                                class="collapse {{ $language->is_default == 1 ? 'show' : '' }}"
                                                aria-labelledby="heading{{ $language->id }}" data-parent="#accordion">
                                                <div
                                                    class="version-body {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                    <div class="row">
                                                        @php
                                                            $product_labels = App\Models\User\Label::where([
                                                                ['user_id', Auth::guard('web')->user()->id],
                                                                ['language_id', $language->id],
                                                            ])->get();
                                                        @endphp
                                                        <div class="col-lg-8">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">

                                                                <label
                                                                    class="d-flex align-items-center justify-content-between">
                                                                    <span>{{ __('Title') }} <span
                                                                            class="text-danger">**</span></span>
                                                                    <button type="button"
                                                                        class="btn btn-sm btn-primary ai-field-btn"
                                                                        data-field="title"
                                                                        data-lang="{{ $language->code }}"
                                                                        data-title="{{ __('Generate Title') }}">
                                                                        <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                                    </button>
                                                                </label>

                                                                <input type="text"
                                                                    class="form-control {{ $lang->rtl == 1 ? 'important_rtl text-right' : 'important_ltr' }}"
                                                                    name="{{ $language->code }}_title"
                                                                    placeholder="{{ __('Enter Title') }}">
                                                            </div>
                                                        </div>

                                                        <div class="col-lg-4">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                                                <label>{{ __('Product Label') }}</label>
                                                                <select name="{{ $language->code }}_label_id"
                                                                    class="form-control">
                                                                    <option value="" selected>
                                                                        {{ __('Select product label') }}
                                                                    </option>
                                                                    @foreach ($product_labels as $product_label)
                                                                        <option value="{{ $product_label->id }}">
                                                                            {{ $product_label->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">

                                                                <label
                                                                    class="d-flex align-items-center justify-content-between">
                                                                    <span>{{ __('Summary') }} <span
                                                                            class="text-danger">**</span></span>

                                                                    <button type="button"
                                                                        class="btn btn-sm btn-primary ai-field-btn"
                                                                        data-field="summary"
                                                                        data-lang="{{ $language->code }}"
                                                                        data-title="{{ __('Generate Summary') }} ({{ $language->name }})">
                                                                        <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                                    </button>
                                                                </label>
                                                                <textarea class="form-control {{ $lang->rtl == 1 ? 'important_rtl text-right' : 'important_ltr' }}"
                                                                    name="{{ $language->code }}_summary" placeholder="{{ __('Enter Summary') }}" rows="8"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">

                                                                <label
                                                                    class="d-flex align-items-center justify-content-between">
                                                                    <span>{{ __('Description') }} <span
                                                                            class="text-danger">**</span></span>

                                                                    <button type="button"
                                                                        class="btn btn-sm btn-primary ai-field-btn"
                                                                        data-field="description"
                                                                        data-lang="{{ $language->code }}"
                                                                        data-title="{{ __('Generate Description') }} ({{ $language->name }})">
                                                                        <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                                    </button>
                                                                </label>
                                                                <textarea id="{{ $language->code }}_PostContent" class="form-control summernote"
                                                                    name="{{ $language->code }}_description" placeholder="{{ __('Enter Description') }}" data-height="300"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">

                                                                <label
                                                                    class="d-flex align-items-center justify-content-between">
                                                                    <span>{{ __('Meta Keywords') }}</span>

                                                                    <button type="button"
                                                                        class="btn btn-sm btn-primary ai-field-btn"
                                                                        data-field="meta_keywords"
                                                                        data-lang="{{ $language->code }}"
                                                                        data-title="{{ __('Generate Meta Keywords') }} ({{ $language->name }})">
                                                                        <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                                    </button>
                                                                </label>
                                                                <input class="form-control"
                                                                    name="{{ $language->code }}_meta_keywords"
                                                                    placeholder="{{ __('Enter Meta Keywords') }}"
                                                                    data-role="tagsinput">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div
                                                                class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">

                                                                <label
                                                                    class="d-flex align-items-center justify-content-between">
                                                                    <span>{{ __('Meta Description') }}</span>

                                                                    <button type="button"
                                                                        class="btn btn-sm btn-primary ai-field-btn"
                                                                        data-field="meta_description"
                                                                        data-lang="{{ $language->code }}"
                                                                        data-title="{{ __('Generate Meta Description') }} ({{ $language->name }})">
                                                                        <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                                    </button>
                                                                </label>
                                                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                                                    placeholder="{{ __('Enter Meta Description') }}"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            @php $currLang = $language; @endphp
                                                            @foreach ($languages as $lang)
                                                                @continue($lang->id == $currLang->id)
                                                                <div class="form-check py-0">
                                                                    <label class="form-check-label">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $lang->id }}', event)">
                                                                        <span
                                                                            class="form-check-sign">{{ __('Clone for') }}
                                                                            <strong
                                                                                class="text-capitalize text-secondary">{{ $lang->name }}</strong>
                                                                            {{ __('language') }}</span>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button type="submit" form="itemForm"
                                    class="btn btn-success">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- thumbnail --}}
    <p class="d-none" id="blob_image"></p>
    <div class="modal fade" id="thumbnail-image-modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h2>{{ __('Thumbnail') }}*</h2>
                    <button role="button" class="close btn btn-secondary mr-2 destroy-cropper d-none text-white"
                        data-dismiss="modal" aria-label="Close">
                        {{ __('Crop') }}
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        @php
                            $d_none = 'none';
                        @endphp
                        <div class="thumb-preview" style="background: {{ $d_none }}">
                            <img src="{{ asset('assets/admin/img/noimage.jpg') }}"
                                data-no_image="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..."
                                class="uploaded-thumbnail-img" id="image">
                        </div>
                        <div class="mt-3">
                            <div role="button" class="btn btn-primary btn-sm  fw-bold upload-btn">
                                {{ __('Choose Image') }}
                                <input type="file" class="thumbnail-input" name="thumbnail-image" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- thumbnail end --}}


    {{-- AI Slider Image Modal --}}
    <div class="modal modal-v2 fade" id="aiSliderModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Generate Slider Images') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="mb-1">{{ __('Prompt') }} <span class="text-danger">*</span></label>
                        <textarea id="ai_slider_prompt" class="form-control" rows="5"
                            placeholder="{{ __('Example') . ': ' . __('800x800 product photo, premium studio lighting, clean background, ecommerce style...') }}"></textarea>
                    </div>

                    <div class="form-group mb-0">
                        <label class="mb-1">{{ __('How many images') . '?' }} <span class="text-danger">*</span></label>
                        <input type="number" id="ai_slider_count" class="form-control" min="1" value="3">
                        <small class="text-muted text-muted-v2 d-block mt-1">
                            {{ __('Tip: 1-4 recommended for performance') . '.' }}
                        </small>
                    </div>

                    <div id="aiSliderErr" class="text-danger mt-2 d-none"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary" id="aiSliderConfirmBtn">
                        <i class="fas fa-magic mr-1"></i> {{ __('Generate') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/user/js/ai-image-modal.js') }}"></script>
    <script src="{{ asset('assets/user/js/ai-form-generator.js') }}"></script>
    <script>
        "use strict";
        const currUrl = "{{ url()->current() }}";
        const fullUrl = "{!! url()->full() !!}";
        const uploadSliderImage = "{{ route('user.item.slider') }}";
        const rmvSliderImage = "{{ route('user.item.slider-remove') }}";
        const rmvDbSliderImage = "{{ route('user.item.db-slider-remove') }}";

        // generate content 
        AiFormGenerator.init({
            openBtn: '.ai-field-btn',
            modalId: '#aiItemSeoModal',
            modalTitleEl: '#aiItemSeoModalTitle',

            confirmBtn: '#aiItemSeoConfirmBtn',
            endpoint: "{{ route('user.ai.generate.content') }}",

            prompt: {
                from: '#ai_item_prompt'
            },

            hiddenField: '#ai_item_field',
            hiddenLang: '#ai_item_lang',

            context: {},
            outputs: {},
            inputSuffix: '',

            extra: {
                mode: () => 'item_seo'
            }
        });
    </script>
    @php
        $test = $languages->pluck('code')->toArray();
    @endphp

    <script src="{{ asset('assets/user/js/dropzone-slider.js') }}"></script>
    <script src="{{ asset('assets/admin/js/plugin/cropper.js') }}"></script>
    <script src="{{ asset('assets/user/js/cropper-init.js') }}"></script>
    <script src="{{ asset('assets/user/js/ai-slider-dropzone.js') }}"></script>
@endsection

@extends('user.layout')

@includeIf('user.partials.rtl-style')

@section('content')
    <div class="home-page-text">
        <div class="page-header">
            <h4 class="page-title">{{ __('Images & Texts') }}</h4>
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('user-dashboard') }}">
                        <i class="flaticon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">{{ __('Pages') }}</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">{{ __('Home Page') }}</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="#">{{ __('Images & Texts') }}</a>
                </li>
            </ul>
        </div>

        <form id="ajaxForm" action="{{ route('user.home.section.update', $language->id) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="card">
                <div class="card-footer text-center">
                    <div class="row">
                        <div class="col-lg-10">
                            <div class="card-title text-left"> {{ __('Images & Texts') }}
                            </div>
                        </div>
                        <div class="col-lg-2">
                            @if (!empty($u_langs))
                                <select name="language" class="form-control"
                                    onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                                    <option value="" selected disabled>
                                        {{ __('Select a Language') }}
                                    </option>
                                    @foreach ($u_langs as $lang)
                                        <option value="{{ $lang->code }}"
                                            {{ $lang->code == request()->input('language') ? 'selected' : '' }}>
                                            {{ $lang->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">

                <!--category section -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card-title text-warning">{{ __('Category Section') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="d-flex align-items-center justify-content-between">
                                            <span>{{ __('Category Section Title') }}</span>
                                            <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                data-field="category_section_title" data-lang="{{ $language->code }}"
                                                data-title="{{ __('Category Section Title') }}">
                                                <i class="fas fa-magic"></i> {{ __('Generate') }}
                                            </button>
                                        </label>
                                        <input name="category_section_title" class="form-control"
                                            value="{{ $ubs->category_section_title ?? '' }}">
                                        <p id="errcategory_section_title" class="em text-danger mb-0"></p>
                                    </div>
                                </div>
                                @if ($setting->theme == 'fashion' || $setting->theme == 'furniture' || $setting->theme == 'electronics')
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="d-flex align-items-center justify-content-between">
                                                <span>{{ __('Category Section Subtitle') }}</span>
                                                <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                    data-field="category_section_subtitle"
                                                    data-lang="{{ $language->code }}"
                                                    data-title="{{ __('Category Section Subtitle') }}">
                                                    <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                </button>
                                            </label>
                                            <input name="category_section_subtitle" class="form-control"
                                                value="{{ $ubs->category_section_subtitle ?? '' }}">
                                            <p id="errcategory_section_subtitle" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!--Latest Product -->
                @if ($setting->theme == 'kids' || $setting->theme == 'electronics')
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="card-title text-warning">
                                            {{ __('Latest Product Section') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="d-flex align-items-center justify-content-between">
                                                <span>{{ __('Latest Product Section Title') }}</span>
                                                <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                    data-field="latest_product_section_title"
                                                    data-lang="{{ $language->code }}"
                                                    data-title="{{ __('Latest Product Section Title') }}">
                                                    <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                </button>
                                            </label>

                                            <input name="latest_product_section_title" class="form-control"
                                                value="{{ $ubs->latest_product_section_title ?? '' }}">
                                            <p id="errlatest_product_section_title" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <!--toprated & selling section -->

                @php
                    $allow_top_selling = ['furniture', 'vegetables', 'manti', 'jewellery', 'pet'];
                @endphp
                @if (in_array($setting->theme, $allow_top_selling))
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="card-title text-warning">
                                            @if ($setting->theme == 'jewellery')
                                                {{ __('Top Selling') }}
                                            @else
                                                {{ __('Top Selling & Rated') }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @php
                                        $allow_theme = ['furniture', 'vegetables', 'manti', 'jewellery', 'pet'];
                                    @endphp
                                    @if (in_array($setting->theme, $allow_theme))
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="d-flex align-items-center justify-content-between">
                                                    <span>
                                                        @if ($setting->theme == 'jewellery')
                                                            {{ __('Top Selling Product Section Title') }}
                                                        @else
                                                            {{ __('Top Rated Product Section Title') }}
                                                        @endif
                                                    </span>
                                                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                        data-field="top_rated_product_section_title"
                                                        data-lang="{{ $language->code }}"
                                                        data-title="@if ($setting->theme == 'jewellery') {{ __('Top Selling Product Section Title') }}@else{{ __('Top Rated Product Section Title') }} @endif">
                                                        <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                    </button>
                                                </label>

                                                <input name="top_rated_product_section_title" class="form-control"
                                                    value="{{ $ubs->top_rated_product_section_title ?? '' }}">
                                                <p id="errtop_rated_product_section_title" class="em text-danger mb-0">
                                                </p>
                                            </div>
                                        </div>
                                        @php
                                            $not_allow_top_selling = ['manti', 'pet'];
                                        @endphp
                                        @if (!in_array($setting->theme, $not_allow_top_selling))
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="d-flex align-items-center justify-content-between">
                                                        <span>
                                                            @if ($setting->theme == 'jewellery')
                                                                {{ __('Top Selling Product Section Subtitle') }}
                                                            @else
                                                                {{ __('Top Selling Product Section Title') }}
                                                            @endif
                                                        </span>
                                                        <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                            data-field="top_selling_product_section_title"
                                                            data-lang="{{ $language->code }}"
                                                            data-title="@if ($setting->theme == 'jewellery') {{ __('Top Selling Product Section Subtitle') }}@else{{ __('Top Selling Product Section Title') }} @endif">
                                                            <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                        </button>
                                                    </label>
                                                    <input name="top_selling_product_section_title" class="form-control"
                                                        value="{{ $ubs->top_selling_product_section_title ?? '' }}">
                                                    <p id="errtop_selling_product_section_title"
                                                        class="em text-danger mb-0">
                                                    </p>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!--toprated-->
                @php
                    $allow_top_rated_section = ['skinflow'];
                @endphp
                @if (in_array($setting->theme, $allow_top_rated_section))
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="card-title text-warning">
                                            {{ __('Top Rated Section') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="d-flex align-items-center justify-content-between">
                                                <span>{{ __('Top Rated Product Section Title') }}</span>
                                                <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                    data-field="top_rated_product_section_title"
                                                    data-lang="{{ $language->code }}"
                                                    data-title="{{ __('Top Rated Product Section Title') }}">
                                                    <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                </button>
                                            </label>
                                            <input name="top_rated_product_section_title" class="form-control"
                                                value="{{ $ubs->top_rated_product_section_title ?? '' }}">
                                            <p id="errtop_rated_product_section_title" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="d-flex align-items-center justify-content-between">
                                                <span>{{ __('Top Rated Product Section Subtitle') }}</span>
                                                <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                    data-field="top_rated_product_section_subtitle"
                                                    data-lang="{{ $language->code }}"
                                                    data-title="{{ __('Top Rated Product Section Subtitle') }}">
                                                    <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                </button>
                                            </label>
                                            <input name="top_rated_product_section_subtitle" class="form-control"
                                                value="{{ $ubs->top_rated_product_section_subtitle ?? '' }}">
                                            <p id="errtop_rated_product_section_subtitle" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!--call to actions section -->
                @php
                    $not_allow_call_to_action = ['fashion', 'pet', 'jewellery'];
                @endphp
                @if (!in_array($setting->theme, $not_allow_call_to_action))
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="card-title text-warning">
                                            {{ __('Call To Action Section') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div
                                        class="{{ $setting->theme == 'fashion' || $setting->theme == 'vegetables' || $setting->theme == 'electronics' || $setting->theme == 'manti' ? 'col-lg-6' : '' }}">
                                        <div class="form-group">
                                            <label for="image"><strong>
                                                    {{ __('Background Image') }}</strong></label>
                                            <div class="showImage2 ">
                                                <img id="action_section_background_image_preview"
                                                    src="{{ !is_null(@$ubs->action_section_background_image) ? asset('assets/front/img/cta/' . @$ubs->action_section_background_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                    alt="..." class="img-thumbnail">
                                                @if (!is_null(@$ubs->action_section_background_image))
                                                    <x-remove-button
                                                        url="{{ route('user.remove_image', ['language_id' => $language->id]) }}"
                                                        name="action_section_background_image" type="image" />
                                                @endif
                                            </div>
                                            <br>
                                            <input type="hidden" name="ai_generated_action_section_background_image"
                                                id="ai_generated_action_section_background_image">
                                            <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                                                <div role="button" class="btn btn-primary btn-sm upload-btn"
                                                    id="image2">
                                                    {{ __('Choose Image') }}
                                                    <input type="file" class="img-input"
                                                        name="action_section_background_image">
                                                </div>
                                                <button type="button" class="btn btn-info btn-sm" data-ai-image-open
                                                    data-endpoint="{{ route('user.ai.generate.category.image') }}"
                                                    data-target="#action_section_background_image_preview"
                                                    data-hidden="#ai_generated_action_section_background_image"
                                                    data-confirm-text="{{ __('Generate Image') }}">
                                                    <i class="fas fa-magic mr-1"></i> {{ __('Generate Image') }}
                                                </button>
                                            </div>
                                            <p class="text-warning p-0 mb-1">
                                                {{ __('Recommended Image size : 1920X300') }}
                                            </p>
                                            <p id="erraction_section_background_image" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    @if (
                                        $setting->theme == 'fashion' ||
                                            $setting->theme == 'vegetables' ||
                                            $setting->theme == 'electronics' ||
                                            $setting->theme == 'manti')
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="image"><strong>
                                                        {{ __('Side Image') }}</strong></label>
                                                <div class="showImage4">
                                                    <img id="action_section_side_image_preview"
                                                        src="{{ !is_null(@$ubs->action_section_side_image) ? asset('assets/front/img/cta/' . @$ubs->action_section_side_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                        alt="..." class="img-thumbnail">
                                                    @if (!is_null(@$ubs->action_section_side_image))
                                                        <x-remove-button
                                                            url="{{ route('user.remove_image', ['language_id' => $language->id]) }}"
                                                            name="action_section_side_image" type="image" />
                                                    @endif
                                                </div>
                                                <br>
                                                <input type="hidden" name="ai_generated_action_section_side_image"
                                                    id="ai_generated_action_section_side_image">
                                                <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                                                    <div role="button" class="btn btn-primary btn-sm upload-btn"
                                                        id="image4">
                                                        {{ __('Choose Image') }}
                                                        <input type="file" class="img-input"
                                                            name="action_section_side_image">
                                                    </div>
                                                    <button type="button" class="btn btn-info btn-sm" data-ai-image-open
                                                        data-endpoint="{{ route('user.ai.generate.category.image') }}"
                                                        data-target="#action_section_side_image_preview"
                                                        data-hidden="#ai_generated_action_section_side_image"
                                                        data-confirm-text="{{ __('Generate Image') }}">
                                                        <i class="fas fa-magic mr-1"></i> {{ __('Generate Image') }}
                                                    </button>
                                                </div>
                                                <p class="text-warning p-0 mb-1">
                                                    {{ __('Recommended Image size : 400X260') }}
                                                </p>
                                                <p id="erraction_section_side_image" class="mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="d-flex align-items-center justify-content-between">
                                                <span>{{ __('Call to Action Title') }}</span>
                                                <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                    data-field="call_to_action_section_title"
                                                    data-lang="{{ $language->code }}"
                                                    data-title="{{ __('Call to Action Title') }}">
                                                    <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                </button>
                                            </label>

                                            <input class="form-control" name="call_to_action_section_title"
                                                value="{{ @$ubs->call_to_action_section_title }}">
                                            <p id="errcall_to_action_section_title" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>

                                    @if ($setting->theme == 'electronics' || $setting->theme == 'vegetables' || $setting->theme == 'skinflow')
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="d-flex align-items-center justify-content-between">
                                                    <span>{{ __('Call to Action Text') }}</span>
                                                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                        data-field="call_to_action_section_text"
                                                        data-lang="{{ $language->code }}"
                                                        data-title="{{ __('Call to Action Text') }}">
                                                        <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                    </button>
                                                </label>

                                                <input class="form-control" name="call_to_action_section_text"
                                                    value="{{ @$ubs->call_to_action_section_text }}">
                                                <p id="errcall_to_action_section_text" class="mb-0 text-danger em"></p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="d-flex align-items-center justify-content-between">
                                                <span>{{ __('Button Text') }}</span>
                                                <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                    data-field="call_to_action_section_button_text"
                                                    data-lang="{{ $language->code }}"
                                                    data-title="{{ __('Button Text') }}">
                                                    <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                </button>
                                            </label>

                                            <input class="form-control" name="call_to_action_section_button_text"
                                                value="{{ @$ubs->call_to_action_section_button_text }}">
                                            <p id="errcall_to_action_section_button_text" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="d-flex align-items-center justify-content-between">
                                                <span>{{ __('Button URL') }}</span>
                                                <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                    data-field="call_to_action_section_button_url"
                                                    data-lang="{{ $language->code }}"
                                                    data-title="{{ __('Button URL') }}">
                                                    <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                </button>
                                            </label>
                                            <input type="url" class="form-control"
                                                name="call_to_action_section_button_url"
                                                value="{{ @$ubs->call_to_action_section_button_url }}">
                                            <p id="errcall_to_action_section_button_url" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!--video content section -->
                @if ($setting->theme == 'fashion')
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="card-title text-warning">{{ __('Video Section') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="form-group">
                                        <label for="image"><strong>{{ __('Background Image') }}</strong></label>
                                        <div class="showImage5">
                                            <img id="video_background_image_preview_fashion"
                                                src="{{ isset($ubs->video_background_image) ? asset('assets/front/img/hero_slider/' . $ubs->video_background_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                alt="..." class="img-thumbnail">
                                            @if (@$ubs->video_background_image)
                                                <x-remove-button
                                                    url="{{ route('user.remove_image', ['language_id' => $language->id]) }}"
                                                    name="video_background_image" type="image" />
                                            @endif
                                        </div>
                                        <br>
                                        <input type="hidden" name="ai_generated_video_background_image"
                                            id="ai_generated_video_background_image_fashion">
                                        <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                                            <div role="button" class="btn btn-primary btn-sm upload-btn" id="image5">
                                                {{ __('Choose Image') }}
                                                <input type="file" class="img-input" name="video_background_image">
                                            </div>
                                            <button type="button" class="btn btn-info btn-sm" data-ai-image-open
                                                data-endpoint="{{ route('user.ai.generate.category.image') }}"
                                                data-target="#video_background_image_preview_fashion"
                                                data-hidden="#ai_generated_video_background_image_fashion"
                                                data-confirm-text="{{ __('Generate Image') }}">
                                                <i class="fas fa-magic mr-1"></i> {{ __('Generate Image') }}
                                            </button>
                                        </div>
                                        <p id="errvideo_background_image" class="em text-danger mb-0"></p>
                                        <p class="text-warning p-0 mb-1">
                                            @if ($setting->theme === 'furniture')
                                                {{ __('Recommended Image size : 1920X860') }}
                                            @else
                                                {{ __('Recommended Image size : 1920X600') }}
                                            @endif
                                        </p>
                                    </div>


                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="d-flex align-items-center justify-content-between">
                                                <span>{{ __('Video Section Title') }}</span>
                                                <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                    data-field="video_section_title" data-lang="{{ $language->code }}"
                                                    data-title="{{ __('Video Section Title') }}">
                                                    <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                </button>
                                            </label>
                                            <input type="text" class="form-control" name="video_section_title"
                                                value="{{ $ubs->video_section_title ?? null }}">
                                            <p id="errvideo_section_title" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>
                                    @if ($setting->theme != 'fashion')
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="d-flex align-items-center justify-content-between">
                                                    <span>{{ __('Video Section Subtitle') }}</span>
                                                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                        data-field="video_section_subtitle"
                                                        data-lang="{{ $language->code }}"
                                                        data-title="{{ __('Video Section Subtitle') }}">
                                                        <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                    </button>
                                                </label>
                                                <input type="text" class="form-control" name="video_section_subtitle"
                                                    value="{{ $ubs->video_section_subtitle ?? null }}">
                                                <p id="errvideo_section_subtitle" class="em text-danger mb-0"></p>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($setting->theme != 'furniture')
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="d-flex align-items-center justify-content-between">
                                                    <span>{{ __('Button Name') }}</span>
                                                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                        data-field="video_section_button_name"
                                                        data-lang="{{ $language->code }}"
                                                        data-title="{{ __('Button Name') }}">
                                                        <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                    </button>
                                                </label>
                                                <input type="text" class="form-control"
                                                    name="video_section_button_name"
                                                    value="{{ $ubs->video_section_button_name ?? null }}">
                                                <p id="errvideo_section_button_name" class="em text-danger mb-0"></p>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="d-flex align-items-center justify-content-between">
                                                    <span>{{ __('Button URL') }}</span>
                                                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                        data-field="video_section_button_url"
                                                        data-lang="{{ $language->code }}"
                                                        data-title="{{ __('Button URL') }}">
                                                        <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                    </button>
                                                </label>
                                                <input type="url" class="form-control"
                                                    name="video_section_button_url"
                                                    value="{{ $ubs->video_section_button_url ?? null }}">
                                                <p id="errvideo_section_button_url" class="em text-danger mb-0"></p>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($setting->theme == 'electronics' || $setting->theme == 'vegetables')
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="d-flex align-items-center justify-content-between">
                                                    <span>{{ __('Video Section Text') }}</span>
                                                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                        data-field="video_section_text"
                                                        data-lang="{{ $language->code }}"
                                                        data-title="{{ __('Video Section Text') }}">
                                                        <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                    </button>
                                                </label>
                                                <input type="text" class="form-control" name="video_section_text"
                                                    value="{{ $ubs->video_section_text ?? null }}">
                                                <p id="errvideo_section_text" class="em text-danger mb-0"></p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="d-flex align-items-center justify-content-between">
                                                <span>{{ __('Video URL') }}</span>
                                                <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                    data-field="video_url" data-lang="{{ $language->code }}"
                                                    data-title="{{ __('Video URL') }}">
                                                    <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                </button>
                                            </label>
                                            <input type="text" class="form-control" name="video_url"
                                                value="{{ $ubs->video_url ?? null }}">
                                            <p id="errvideo_url" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!--tabs section -->
                @php
                    $not_allow_tabs_section = ['manti', 'pet', 'skinflow', 'jewellery'];
                @endphp
                @if (!in_array($setting->theme, $not_allow_tabs_section))
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="card-title text-warning">{{ __('Tabs Section') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="d-flex align-items-center justify-content-between">
                                                <span>{{ __('Tabs Section Title') }}</span>
                                                <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                    data-field="tab_section_title" data-lang="{{ $language->code }}"
                                                    data-title="{{ __('Tabs Section Title') }}">
                                                    <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                </button>
                                            </label>
                                            <input name="tab_section_title" class="form-control"
                                                value="{{ $ubs->tab_section_title ?? '' }}">
                                            <p id="errtab_section_title" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>
                                    @if ($setting->theme == 'fashion')
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="d-flex align-items-center justify-content-between">
                                                    <span>{{ __('Tabs Section Subtitle') }}</span>
                                                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                        data-field="tab_section_subtitle"
                                                        data-lang="{{ $language->code }}"
                                                        data-title="{{ __('Tabs Section Subtitle') }}">
                                                        <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                    </button>
                                                </label>
                                                <input name="tab_section_subtitle" class="form-control"
                                                    value="{{ $ubs->tab_section_subtitle ?? '' }}">
                                                <p id="errtab_section_subtitle" class="em text-danger mb-0"></p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!--flash section -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="card-title text-warning">{{ __('Flash Section') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @php
                                    $not_allow_flash_background = [
                                        'skinflow',
                                        'jewellery',
                                        'manti',
                                        'kids',
                                        'electronics',
                                        'fashion',
                                        'furniture',
                                        'vegetables',
                                    ];
                                @endphp
                                @if (!in_array($setting->theme, $not_allow_flash_background))
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="image"><strong>{{ __('Background Image') }}</strong></label>
                                            <div class="showImage5">
                                                <img id="flash_section_background_image_preview"
                                                    src="{{ isset($ubs->flash_section_background_image) ? asset('assets/front/img/user/flash_section/' . $ubs->flash_section_background_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                    alt="..." class="img-thumbnail">
                                                @if (@$ubs->flash_section_background_image)
                                                    <x-remove-button
                                                        url="{{ route('user.remove_image', ['language_id' => $language->id]) }}"
                                                        name="flash_section_background_image" type="image" />
                                                @endif
                                            </div>
                                            <br>
                                            <input type="hidden" name="ai_generated_flash_section_background_image"
                                                id="ai_generated_flash_section_background_image">
                                            <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                                                <div role="button" class="btn btn-primary btn-sm upload-btn"
                                                    id="image5">
                                                    {{ __('Choose Image') }}
                                                    <input type="file" class="img-input"
                                                        name="flash_section_background_image">
                                                </div>
                                                <button type="button" class="btn btn-info btn-sm" data-ai-image-open
                                                    data-endpoint="{{ route('user.ai.generate.category.image') }}"
                                                    data-target="#flash_section_background_image_preview"
                                                    data-hidden="#ai_generated_flash_section_background_image"
                                                    data-confirm-text="{{ __('Generate Image') }}">
                                                    <i class="fas fa-magic mr-1"></i> {{ __('Generate Image') }}
                                                </button>
                                            </div>
                                            <p id="errflash_section_background_image" class="em text-danger mb-0"></p>
                                            <p class="text-warning p-0 mb-1">
                                                {{ __('Recommended Image size : 456X471') }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="d-flex align-items-center justify-content-between">
                                            <span>{{ __('Flash Section Title') }}</span>
                                            <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                data-field="flash_section_title" data-lang="{{ $language->code }}"
                                                data-title="{{ __('Flash Section Title') }}">
                                                <i class="fas fa-magic"></i> {{ __('Generate') }}
                                            </button>
                                        </label>
                                        <input name="flash_section_title" class="form-control"
                                            value="{{ $ubs->flash_section_title ?? '' }}">
                                        <p id="errflash_section_title" class="em text-danger mb-0"></p>
                                    </div>
                                </div>
                                @if ($setting->theme == 'fashion' || $setting->theme == 'furniture')
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="d-flex align-items-center justify-content-between">
                                                <span>{{ __('Flash Section Subtitle') }}</span>
                                                <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                    data-field="flash_section_subtitle"
                                                    data-lang="{{ $language->code }}"
                                                    data-title="{{ __('Flash Section Subtitle') }}">
                                                    <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                </button>
                                            </label>
                                            <input name="flash_section_subtitle" class="form-control"
                                                value="{{ $ubs->flash_section_subtitle ?? '' }}">
                                            <p id="errflash_section_subtitle" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!--video content section for furniture and kids theme -->
                @if ($setting->theme == 'furniture' || $setting->theme == 'kids')
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="card-title text-warning">{{ __('Video Section') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="form-group">
                                        <label for="image"><strong>{{ __('Background Image') }}</strong></label>
                                        <div class="showImage5">
                                            <img id="video_background_image_preview_furniture"
                                                src="{{ isset($ubs->video_background_image) ? asset('assets/front/img/hero_slider/' . $ubs->video_background_image) : asset('assets/admin/img/noimage.jpg') }}"
                                                alt="..." class="img-thumbnail">
                                            @if (@$ubs->video_background_image)
                                                <x-remove-button
                                                    url="{{ route('user.remove_image', ['language_id' => $language->id]) }}"
                                                    name="video_background_image" type="image" />
                                            @endif
                                        </div>
                                        <br>
                                        <input type="hidden" name="ai_generated_video_background_image"
                                            id="ai_generated_video_background_image_furniture">
                                        <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                                            <div role="button" class="btn btn-primary btn-sm upload-btn" id="image5">
                                                {{ __('Choose Image') }}
                                                <input type="file" class="img-input" name="video_background_image">
                                            </div>
                                            <button type="button" class="btn btn-info btn-sm" data-ai-image-open
                                                data-endpoint="{{ route('user.ai.generate.category.image') }}"
                                                data-target="#video_background_image_preview_furniture"
                                                data-hidden="#ai_generated_video_background_image_furniture"
                                                data-confirm-text="{{ __('Generate Image') }}">
                                                <i class="fas fa-magic mr-1"></i> {{ __('Generate Image') }}
                                            </button>
                                        </div>
                                        <p id="errvideo_background_image" class="em text-danger mb-0"></p>
                                        <p class="text-warning p-0 mb-1">
                                            @if ($setting->theme === 'furniture')
                                                {{ __('Recommended Image size : 1920X860') }}
                                            @else
                                                {{ __('Recommended Image size : 1920X600') }}
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="d-flex align-items-center justify-content-between">
                                                <span>{{ __('Video Section Title') }}</span>
                                                <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                    data-field="video_section_title" data-lang="{{ $language->code }}"
                                                    data-title="{{ __('Video Section Title') }}">
                                                    <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                </button>
                                            </label>
                                            <input type="text" class="form-control" name="video_section_title"
                                                value="{{ $ubs->video_section_title ?? null }}">
                                            <p id="errvideo_section_title" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>
                                    @if ($setting->theme != 'fashion')
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="d-flex align-items-center justify-content-between">
                                                    <span>{{ __('Video Section Subtitle') }}</span>
                                                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                        data-field="video_section_subtitle"
                                                        data-lang="{{ $language->code }}"
                                                        data-title="{{ __('Video Section Subtitle') }}">
                                                        <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                    </button>
                                                </label>
                                                <input type="text" class="form-control" name="video_section_subtitle"
                                                    value="{{ $ubs->video_section_subtitle ?? null }}">
                                                <p id="errvideo_section_subtitle" class="em text-danger mb-0"></p>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($setting->theme != 'furniture')
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="d-flex align-items-center justify-content-between">
                                                    <span>{{ __('Button Name') }}</span>
                                                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                        data-field="video_section_button_name"
                                                        data-lang="{{ $language->code }}"
                                                        data-title="{{ __('Button Name') }}">
                                                        <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                    </button>
                                                </label>
                                                <input type="text" class="form-control"
                                                    name="video_section_button_name"
                                                    value="{{ $ubs->video_section_button_name ?? null }}">
                                                <p id="errvideo_section_button_name" class="em text-danger mb-0"></p>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="d-flex align-items-center justify-content-between">
                                                    <span>{{ __('Button URL') }}</span>
                                                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                        data-field="video_section_button_url"
                                                        data-lang="{{ $language->code }}"
                                                        data-title="{{ __('Button URL') }}">
                                                        <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                    </button>
                                                </label>
                                                <input type="url" class="form-control"
                                                    name="video_section_button_url"
                                                    value="{{ $ubs->video_section_button_url ?? null }}">
                                                <p id="errvideo_section_button_url" class="em text-danger mb-0"></p>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($setting->theme == 'electronics' || $setting->theme == 'vegetables')
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="d-flex align-items-center justify-content-between">
                                                    <span>{{ __('Video Section Text') }}</span>
                                                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                        data-field="video_section_text"
                                                        data-lang="{{ $language->code }}"
                                                        data-title="{{ __('Video Section Text') }}">
                                                        <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                    </button>
                                                </label>
                                                <input type="text" class="form-control" name="video_section_text"
                                                    value="{{ $ubs->video_section_text ?? null }}">
                                                <p id="errvideo_section_text" class="em text-danger mb-0"></p>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="d-flex align-items-center justify-content-between">
                                                <span>{{ __('Video URL') }}</span>
                                                <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                    data-field="video_url" data-lang="{{ $language->code }}"
                                                    data-title="{{ __('Video URL') }}">
                                                    <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                </button>
                                            </label>
                                            <input type="text" class="form-control" name="video_url"
                                                value="{{ $ubs->video_url ?? null }}">
                                            <p id="errvideo_url" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <!--Featured Product -->
                @php
                    $allow_featured_section = ['manti', 'pet', 'skinflow', 'jewellery'];
                @endphp
                @if (in_array($setting->theme, $allow_featured_section))
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="card-title text-warning">
                                            {{ __('Featured Section') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="d-flex align-items-center justify-content-between">
                                                <span>{{ __('Featured Section Title') }}</span>
                                                <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                    data-field="featured_section_title"
                                                    data-lang="{{ $language->code }}"
                                                    data-title="{{ __('Featured Section Title') }}">
                                                    <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                </button>
                                            </label>
                                            <input name="featured_section_title" class="form-control"
                                                value="{{ $ubs->featured_section_title ?? '' }}">
                                            <p id="errfeatured_section_title" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <!--features content section -->
                @php
                    $allow_features_section = ['vegetables', 'pet'];
                @endphp
                @if (in_array($setting->theme, $allow_features_section))
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="card-title text-warning">
                                            {{ __('Features Section') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="mb-2 pl-0">
                                                <label for="image"><strong>{{ __('Image') }}</strong></label>
                                            </div>
                                            <div class="showImage  mb-3 pl-0 pr-0">
                                                <img id="featured_img_preview"
                                                    src="{{ !is_null(@$ubs->featured_img) ? asset('assets/front/img/user/feature/' . $ubs->featured_img) : asset('assets/admin/img/noimage.jpg') }}"
                                                    alt="..." class="img-thumbnail">
                                                @if (!is_null(@$ubs->featured_img))
                                                    <x-remove-button
                                                        url="{{ route('user.remove_image', ['language_id' => $language->id]) }}"
                                                        name="featured_img" type="image" />
                                                @endif
                                            </div>
                                            <br>
                                            <input type="hidden" name="ai_generated_featured_img"
                                                id="ai_generated_featured_img">
                                            <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                                                <div role="button" class="btn btn-primary btn-sm upload-btn"
                                                    id="image">
                                                    {{ __('Choose Image') }}
                                                    <input type="file" class="img-input" name="featured_img">
                                                </div>
                                                <button type="button" class="btn btn-info btn-sm" data-ai-image-open
                                                    data-endpoint="{{ route('user.ai.generate.category.image') }}"
                                                    data-target="#featured_img_preview"
                                                    data-hidden="#ai_generated_featured_img"
                                                    data-confirm-text="{{ __('Generate Image') }}">
                                                    <i class="fas fa-magic mr-1"></i> {{ __('Generate Image') }}
                                                </button>
                                            </div>
                                            <p class="p-0 text-warning">
                                                {{ __('Recommended Image size : 680X670') }}
                                            </p>
                                        </div>
                                    </div>
                                    @if ($setting->theme != 'vegetables')
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <div class="mb-2 pl-0">
                                                    <label
                                                        for="image"><strong>{{ __('Background Image') }}</strong></label>
                                                </div>
                                                <div class="showImage6  mb-3 pl-0 pr-0">
                                                    <img id="featured_background_img_preview"
                                                        src="{{ !is_null(@$ubs->featured_background_img) ? asset('assets/front/img/user/feature/' . $ubs->featured_background_img) : asset('assets/admin/img/noimage.jpg') }}"
                                                        alt="..." class="img-thumbnail">
                                                    @if (!is_null(@$ubs->featured_background_img))
                                                        <x-remove-button
                                                            url="{{ route('user.remove_image', ['language_id' => $language->id]) }}"
                                                            name="featured_background_img" type="image" />
                                                    @endif
                                                </div>
                                                <br>
                                                <input type="hidden" name="ai_generated_featured_background_img"
                                                    id="ai_generated_featured_background_img">
                                                <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                                                    <div role="button" class="btn btn-primary btn-sm upload-btn"
                                                        id="image6">
                                                        {{ __('Choose Image') }}
                                                        <input type="file" class="img-input"
                                                            name="featured_background_img">
                                                    </div>
                                                    <button type="button" class="btn btn-info btn-sm" data-ai-image-open
                                                        data-endpoint="{{ route('user.ai.generate.category.image') }}"
                                                        data-target="#featured_background_img_preview"
                                                        data-hidden="#ai_generated_featured_background_img"
                                                        data-confirm-text="{{ __('Generate Image') }}">
                                                        <i class="fas fa-magic mr-1"></i> {{ __('Generate Image') }}
                                                    </button>
                                                </div>
                                                <p class="p-0 text-warning">
                                                    {{ __('Recommended Image size : 680X670') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="d-flex align-items-center justify-content-between">
                                                    <span>{{ __('Features Section Title') }}</span>
                                                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                                        data-field="features_section_title"
                                                        data-lang="{{ $language->code }}"
                                                        data-title="{{ __('Features Section Title') }}">
                                                        <i class="fas fa-magic"></i> {{ __('Generate') }}
                                                    </button>
                                                </label>
                                                <input name="features_section_title" class="form-control"
                                                    value="{{ $ubs->features_section_title ?? '' }}">
                                                <p id="errfeatures_section_title" class="em text-danger mb-0"></p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </form>
        <div class="card">
            <div class="card-footer text-center">
                <button type="submit" id="submitBtn" class="btn btn-success">{{ __('Update') }}</button>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/user/js/ai-image-modal.js') }}"></script>
    <script src="{{ asset('assets/user/js/ai-form-generator.js') }}"></script>
    <script>
        "use strict";

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
                mode: () => 'home_page_text'
            }
        });
    </script>
    <script src="{{ asset('assets/user/js/image-text.js') }}"></script>
@endsection

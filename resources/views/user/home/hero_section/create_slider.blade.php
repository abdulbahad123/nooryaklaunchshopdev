@extends('user.layout')

@php
  $userDefaultLang = \App\Models\User\Language::where([
      ['user_id', \Illuminate\Support\Facades\Auth::id()],
      ['is_default', 1],
  ])->first();
  $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
@endphp

@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Slider') }}</h4>
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
        <a href="#">{{ __('Hero Section') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Add Slider') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Add Slider') }}</div>

          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('user.home_page.hero.slider_version') . '?language=' . $userDefaultLang->code }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-8 m-auto">
              <form id="sliderVersionForm" action="{{ route('user.home_page.hero.store_slider_info') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                  <label for="">{{ __('Language') }} <span class="text-danger">**</span></label>
                  <select id="language" name="user_language_id" class="form-control">
                    <option value="" selected disabled>
                      {{ __('Select a language') }}</option>
                    @foreach ($userLanguages as $lang)
                      <option value="{{ $lang->id }}" data-code="{{ $lang->code }}">{{ $lang->name }}</option>
                    @endforeach
                  </select>
                  @if ($errors->has('user_language_id'))
                    <p class="mt-2 mb-0 text-danger">{{ $errors->first('user_language_id') }}</p>
                  @endif
                </div>
                <input type="hidden" name="is_static" class="form-control" value="0">
                @if ($userBs->theme === 'vegetables' || $userBs->theme === 'electronics' || $userBs->theme === 'manti' || $userBs->theme == 'skinflow' || $userBs->theme === 'clothing')
                  <div class="form-group">
                    <div class="col-12 mb-2 pl-0">
                      <label for="image"><strong>{{ __('Background Image') }} <span
                            class="text-danger">**</span></strong></label>
                    </div>
                    <div class="col-md-12 showImage mb-3 pl-0 pr-0">
                      <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="img-thumbnail"
                        id="hero_slider_preview">
                    </div><br>
                    <input type="hidden" name="ai_generated_slider_img" id="ai_generated_slider_img">
                    <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                      <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="slider_img">
                      </div>
                      <button type="button" class="btn btn-info btn-sm" data-ai-image-open
                        data-endpoint="{{ route('user.ai.generate.category.image') }}"
                        data-target="#hero_slider_preview" data-hidden="#ai_generated_slider_img"
                        data-confirm-text="{{ __('Generate Image') }}">
                        <i class="fas fa-magic mr-1"></i> {{ __('Generate Image') }}
                      </button>
                    </div>

                    @if ($errors->has('slider_img'))
                      <p class="mt-2 mb-0 text-danger">{{ $errors->first('slider_img') }}</p>
                    @endif
                    <p class="text-warning p-0 mb-1">
                        @if ($userBs->theme === 'manti' || $userBs->theme === 'clothing')
                        {{ __('Recommended Image size : 1440X576') }}
                      @else
                        {{ __('Recommended Image size : 870X590') }}
                      @endif
                    </p>
                  </div>
                @endif

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="d-flex align-items-center justify-content-between">
                        <span>{{ __('Title') }}</span>
                        <button type="button" class="btn btn-sm btn-primary ai-field-btn" data-field="title"
                          data-lang="" data-title="{{ __('Title') }}">
                          <i class="fas fa-magic"></i> {{ __('Generate') }}
                        </button>
                      </label>
                      <input type="text" class="form-control" name="title"
                        placeholder="{{ __('Enter Slider Title') }}">
                      @if ($errors->has('title'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('title') }}</p>
                      @endif
                      <p class="text-warning p-0 mb-1">
                        @if ($userBs->theme === 'furniture')
                          {{ __('wrap the text with <span></span> to have underline under the text') }}
                        @endif
                      </p>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="d-flex align-items-center justify-content-between">
                        <span>{{ __('Subtitle') }}</span>
                        <button type="button" class="btn btn-sm btn-primary ai-field-btn" data-field="subtitle"
                          data-lang="" data-title="{{ __('Subtitle') }}">
                          <i class="fas fa-magic"></i> {{ __('Generate') }}
                        </button>
                      </label>
                      <input type="text" class="form-control" name="subtitle"
                        placeholder="{{ __('Enter Slider Subtitle') }}">
                      @if ($errors->has('subtitle'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('subtitle') }}</p>
                      @endif
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="">{{ __('Button Name') }}</label>
                      <input type="text" class="form-control" name="btn_name"
                        placeholder="{{ __('Enter Slider Button Name') }}">
                      @if ($errors->has('btn_name'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('btn_name') }}</p>
                      @endif
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <div class="form-group">
                      <label>{{ __('Button URL') }}</label>
                      <input type="text" class="form-control" name="btn_url"
                        placeholder="{{ __('Enter Slider Button URL') }}">
                      @if ($errors->has('btn_url'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('btn_url') }}</p>
                      @endif
                    </div>
                  </div>

                  @if ($userBs->theme == 'furniture')
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('Video URL') }}</label>
                        <input type="url" class="form-control" name="video_url"
                          placeholder="{{ __('Enter Video URL') }}">
                        @if ($errors->has('video_url'))
                          <p class="mt-2 mb-0 text-danger">{{ $errors->first('video_url') }}</p>
                        @endif
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>{{ __('Video Button Text') }}</label>
                        <input type="text" class="form-control" name="video_button_text"
                          placeholder="{{ __('Enter Video Button Text') }}">
                        @if ($errors->has('video_button_text'))
                          <p class="mt-2 mb-0 text-danger">{{ $errors->first('video_button_text') }}</p>
                        @endif
                      </div>
                    </div>
                  @endif

                </div>

                @if ($userBs->theme === 'vegetables' || $userBs->theme === 'electronics' || $userBs->theme === 'manti' || $userBs->theme === 'clothing')
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label class="d-flex align-items-center justify-content-between">
                          <span>{{ __('Text') }}</span>
                          <button type="button" class="btn btn-sm btn-primary ai-field-btn" data-field="text"
                            data-lang="" data-title="{{ __('Text') }}">
                            <i class="fas fa-magic"></i> {{ __('Generate') }}
                          </button>
                        </label>
                        <input type="text" class="form-control" name="text"
                          placeholder="{{ __('Enter Slider Text') }}">
                        @if ($errors->has('text'))
                          <p class="mt-2 mb-0 text-danger">{{ $errors->first('text') }}</p>
                        @endif
                      </div>
                    </div>
                  </div>
                @endif


                <div class="row">

                  <div class="col-lg-12">
                    <div class="form-group">
                      <label for="">{{ __('Serial Number') }} <span class="text-danger">**</span></label>
                      <input type="number" class="form-control" name="serial_number"
                        placeholder="{{ __('Enter Slider Serial Number') }}">
                      @if ($errors->has('serial_number'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('serial_number') }}</p>
                      @endif
                      <p class="text-warning mt-2 mb-0">
                        {{ __('The higher the serial number is, the later the slider will be shown.') }}
                      </p>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="sliderVersionForm" class="btn btn-primary">
                {{ __('Save') }}
              </button>
            </div>
          </div>
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

    function updateHeroSliderAiLang() {
      var code = $('#language option:selected').data('code') || '';
      $('.ai-field-btn').attr('data-lang', code);
    }

    $(document).on('change', '#language', updateHeroSliderAiLang);
    updateHeroSliderAiLang();

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
@endsection

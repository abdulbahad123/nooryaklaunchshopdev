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
    <h4 class="page-title">{{ __('404 Page') }}</h4>
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
        <a href="#">{{ __('404 Page') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-10">
              <div class="card-title">{{ __('Update 404 Page') }}</div>
            </div>

            <div class="col-lg-2">
              @if (!is_null($userDefaultLang))
                @if (!empty($userLanguages))
                  <select name="userLanguage" id="not_found_language" class="form-control"
                    onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                    <option value="" selected disabled>
                      {{ __('Select a Language') }}</option>
                    @foreach ($userLanguages as $lang)
                      <option value="{{ $lang->code }}" data-code="{{ $lang->code }}"
                        {{ $lang->code == request()->input('language') ? 'selected' : '' }}>{{ $lang->name }}</option>
                    @endforeach
                  </select>
                @endif
              @endif
            </div>
          </div>
        </div>

        <div class="card-body pt-5 pb-5">
          <div class="row">
            <div class="col-lg-6 m-auto">
              <form id="ajaxForm"
                action="{{ route('user.not_found_page.update', ['language' => request()->input('language')]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                  <div class="mb-2">
                    <label for="image">
                      <strong>{{ __('Image') }}*</strong>
                    </label>
                  </div>
                  <div class="showImage mb-3">
                    <img
                      src="{{ @$image ? asset('assets/user-front/images/' . $image) : asset('assets/admin/img/noimage.jpg') }}"
                      alt="..." class="img-thumbnail" id="not_found_image_preview">
                  </div>
                  <br>
                  <input type="hidden" name="ai_generated_image" id="ai_generated_not_found_image">
                  <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                    <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                      {{ __('Choose Image') }}
                      <input type="file" class="img-input" name="page_not_found_image">
                    </div>
                    <button type="button" class="btn btn-info btn-sm" data-ai-image-open
                      data-endpoint="{{ route('user.ai.generate.category.image') }}"
                      data-target="#not_found_image_preview"
                      data-hidden="#ai_generated_not_found_image"
                      data-confirm-text="{{ __('Generate Image') }}">
                      <i class="fas fa-magic mr-1"></i> {{ __('Generate Image') }}
                    </button>
                  </div>
                  <p id="errpage_not_found_image" class="mb-0 text-danger em"></p>
                  <p class="text-warning p-0 mb-1">
                    {{ __('Recommended Image size : 600X400') }}
                  </p>
                </div>

                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Title') }}*</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn" data-field="user_not_found_title"
                      data-lang="" data-title="{{ __('Title') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>
                  <input class="form-control" name="user_not_found_title"
                    value="{{ $data->user_not_found_title ?? null }}" />
                  <p id="erruser_not_found_title" class="mb-0 text-danger em"></p>
                </div>

                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Subtitle') }}*</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                      data-field="user_not_found_subtitle" data-lang="" data-title="{{ __('Subtitle') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>
                  <input class="form-control" name="user_not_found_subtitle"
                    value="{{ $data->user_not_found_subtitle ?? null }}" />
                  <p id="erruser_not_found_subtitle" class="mb-0 text-danger em"></p>
                </div>
              </form>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" id="submitBtn" class="btn btn-success">
                {{ __('Update') }}
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

    function updateNotFoundAiLang() {
      var code = $('#not_found_language option:selected').data('code') || $('#not_found_language').val() || '';
      $('.ai-field-btn').attr('data-lang', code);
    }

    $(document).on('change', '#not_found_language', updateNotFoundAiLang);
    updateNotFoundAiLang();

    var notFoundAiScope = null;
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
      outputs: function() {
        if (!notFoundAiScope || !notFoundAiScope.length) return {};
        return {
          user_not_found_title: notFoundAiScope.find('[name="user_not_found_title"]'),
          user_not_found_subtitle: notFoundAiScope.find('[name="user_not_found_subtitle"]')
        };
      },
      inputSuffix: '',

      extra: {
        mode: () => 'home_page_text'
      },
      onOpen: function($btn) {
        notFoundAiScope = $btn.closest('form');
        if (!notFoundAiScope.length) {
          notFoundAiScope = $btn.closest('.modal');
        }
      }
    });
  </script>
@endsection

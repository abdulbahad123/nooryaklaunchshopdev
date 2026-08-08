@php
  use Illuminate\Support\Facades\Auth;
  use Illuminate\Support\Facades\DB;
@endphp

@extends('user.layout')
@section('styles')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/cropper.css') }}">
@endsection
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Post') }}</h4>
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
        <a href="#">{{ __('Blog') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="{{ route('user.blog.index', ['language' => request()->input('language')]) }}">{{ __('Posts') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Add Post') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">{{ __('Add Post') }}</div>
          <button type="button" class="btn btn-primary btn-sm float-right ml-2 ai-field-btn"
            data-field="" data-lang="" data-title="{{ __('Generate All Content') }}" id="aiGenerateItemSeoBtn">
            <i class="fas fa-magic mr-1"></i> {{ __('Generate All Content') }}
          </button>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('user.blog.index', ['language' => $de_lang->code]) }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-9 m-auto">
              <div class="alert alert-danger pb-1 d-none" id="postErrors">
                <ul></ul>
              </div>
              <form id="itemForm" action="{{ route('user.blog.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-lg-12">
                    <div class="form-group px-0">
                      <div class="mb-2">
                        <label for="image"><strong>{{ __('Image') }} <span
                              class="text-danger">**</span></strong></label>
                      </div>
                    <div class="showImage mb-3  pl-0 pr-0">
                      <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..."
                        class="cropped-thumbnail-image">
                    </div>
                    <br>
                      <input type="hidden" name="ai_generated_image" id="ai_generated_blog_image">
                      <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                          data-target="#thumbnail-image-modal">{{ __('Choose Image') }}</button>
                        <button type="button" class="btn btn-info" data-ai-image-open
                          data-endpoint="{{ route('user.ai.generate.category.image') }}"
                          data-target=".showImage img.cropped-thumbnail-image"
                          data-hidden="#ai_generated_blog_image" data-confirm-text="{{ __('Generate Image') }}">
                          <i class="fas fa-magic mr-1"></i> {{ __('Generate Image') }}
                        </button>
                      </div>
                  </div>
                  <p class="text-warning p-0 mb-1">
                    {{ __('Recommended Image size : 900X600') }}
                  </p>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group px-0">
                      <label>{{ __('Serial Number') }} <span class="text-danger">**</span></label>
                      <input class="form-control" type="number" name="serial_number"
                        placeholder="{{ __('Enter Serial Number') }}">
                      <p class="text-warning mt-2 mb-0">
                        <small>{{ __('The higher the serial number is, the later the blog will be shown.') }}</small>
                      </p>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group px-0">
                      <label>{{ __('Status') }} <span class="text-danger">**</span></label>
                      <select name="status" class="form-control">
                        <option value="1">{{ __('Active') }}</option>
                        <option value="0">{{ __('Deactive') }}</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label for="">{{ __('Category') }} <span class="text-danger">**</span></label>
                      <select name="category_id" class="form-control">
                        <option selected disabled>{{ __('Select Category') }}
                        </option>
                        @foreach ($categories as $category)
                          <option value="{{ $category->unique_id }}">{{ $category->name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>

                <div class="mt-3" id="accordion">
                  @foreach ($userLangs as $language)
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
                        <div class="card-body {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                          <div class="row">
                            <div class="col-lg-8">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label class="d-flex align-items-center justify-content-between">
                                  <span>{{ __('Title') }} <span class="text-danger">**</span></span>
                                  <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                    data-field="title" data-lang="{{ $language->code }}"
                                    data-title="{{ __('Generate Title') }} ({{ $language->name }})">
                                    <i class="fas fa-magic"></i> {{ __('Generate') }}
                                  </button>
                                </label>
                                <input type="text" class="form-control" name="{{ $language->code }}_title"
                                  placeholder="{{ __('Enter Title') }}">
                                <p class="text-danger mt-2 em" id="err{{ $language->code }}_title"></p>
                              </div>
                            </div>

                            <div class="col-lg-4">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label>{{ __('Author') }} <span class="text-danger">**</span></label>
                                <input type="text" class="form-control" name="{{ $language->code }}_author"
                                  placeholder="{{ __('Enter Author Name') }}">
                                <p class="text-danger mt-2 em" id="err{{ $language->code }}_author"></p>
                              </div>
                            </div>


                            <div class="col-lg-12">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label class="d-flex align-items-center justify-content-between">
                                  <span>{{ __('Content') }} <span class="text-danger">**</span></span>
                                  <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                    data-field="description" data-lang="{{ $language->code }}"
                                    data-title="{{ __('Generate Content') }} ({{ $language->name }})">
                                    <i class="fas fa-magic"></i> {{ __('Generate') }}
                                  </button>
                                </label>
                                <textarea class="form-control summernote" name="{{ $language->code }}_content" id="{{ $language->code }}_content"
                                  placeholder="{{ __('Enter Blog Content') }}" data-height="300"></textarea>

                                <p class="text-danger mt-2 em" id="err{{ $language->code }}_content"></p>
                              </div>
                            </div>

                            <div class="col-lg-12">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label class="d-flex align-items-center justify-content-between">
                                  <span>{{ __('Meta Keywords') }}</span>
                                  <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                    data-field="meta_keywords" data-lang="{{ $language->code }}"
                                    data-title="{{ __('Generate Meta Keywords') }} ({{ $language->name }})">
                                    <i class="fas fa-magic"></i> {{ __('Generate') }}
                                  </button>
                                </label>
                                <input class="form-control" name="{{ $language->code }}_meta_keywords"
                                  placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                              </div>
                            </div>

                            <div class="col-lg-12">
                              <div class="form-group {{ $language->rtl == 1 ? 'rtl text-right' : '' }}">
                                <label class="d-flex align-items-center justify-content-between">
                                  <span>{{ __('Meta Description') }}</span>
                                  <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                                    data-field="meta_description" data-lang="{{ $language->code }}"
                                    data-title="{{ __('Generate Meta Description') }} ({{ $language->name }})">
                                    <i class="fas fa-magic"></i> {{ __('Generate') }}
                                  </button>
                                </label>
                                <textarea class="form-control" name="{{ $language->code }}_meta_description" rows="5"
                                  placeholder="{{ __('Enter Meta Description') }}"></textarea>
                              </div>
                            </div>

                            <div class="col-lg-12">
                              @php $currLang = $language; @endphp
                              @foreach ($userLangs as $lang)
                                @continue($lang->id == $currLang->id)
                                <div class="form-check py-0">
                                  <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox"
                                      onchange="cloneInput('collapse{{ $currLang->id }}', 'collapse{{ $lang->id }}', event)">
                                    <span class="form-check-sign">{{ __('Clone for') }}
                                      <strong class="text-capitalize text-secondary">{{ $lang->name }}</strong>
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
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="itemForm" class="btn btn-success">
                {{ __('Save') }}
              </button>
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
          <h2>{{ __('Thumbnail') }} <span class="text-danger">**</span></h2>
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
              <div role="button" class="btn btn-primary btn-sm upload-btn">
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
@endsection

@section('scripts')
  <script src="{{ asset('assets/admin/js/plugin/cropper.js') }}"></script>
  <script src="{{ asset('assets/user/js/blog-cropper-init.js') }}"></script>
  <script src="{{ asset('assets/user/js/ai-image-modal.js') }}"></script>
  <script src="{{ asset('assets/user/js/ai-form-generator.js') }}"></script>
  <script>
    "use strict";

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
        var map = {};

        $('[name$="_title"]').each(function() {
          map[$(this).attr('name')] = this;
        });
        $('[name$="_meta_keywords"]').each(function() {
          map[$(this).attr('name')] = this;
        });
        $('[name$="_meta_description"]').each(function() {
          map[$(this).attr('name')] = this;
        });
        $('[name$="_content"]').each(function() {
          var name = $(this).attr('name') || '';
          var lang = name.replace(/_content$/, '');
          if (lang) {
            map[lang + '_description'] = this;
          }
        });

        return map;
      },
      inputSuffix: '',

      extra: {
        mode: () => 'item_seo'
      }
    });
  </script>
@endsection

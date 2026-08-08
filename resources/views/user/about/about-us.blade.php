@extends('user.layout')
@includeIf('user.partials.rtl-style')
@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('About') }}</h4>
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
        <a href="#">{{ __('About Us') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('About') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-8">
              <div class="card-title d-inline-block">{{ __('Update About') }}</div>
            </div>
            <div class="col-lg-4">
              @if (!empty($userLanguages))
                <select name="language" id="about_language" class="form-control"
                  onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                  <option value="" selected disabled>{{ __('Select a Language') }}
                  </option>
                  @foreach ($userLanguages as $lang)
                    <option value="{{ $lang->code }}" data-code="{{ $lang->code }}"
                      {{ $lang->code == request()->input('language') ? 'selected' : '' }}>
                      {{ $lang->name }}</option>
                  @endforeach
                </select>
              @endif
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              <form id="about"
                action="{{ route('user.pages.about_us.update', ['language' => request()->input('language')]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-lg-8">
                    <div class="form-group">
                      <div class="mb-2">
                        <label for="image"><strong>{{ __('Image') }}</strong></label>
                      </div>
                      <div class="showImage mb-3">
                        <img
                          src="{{ !is_null(@$data->image) ? asset('assets/front/img/user/about/' . @$data->image) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="img-thumbnail" id="about_image_preview">
                        @if (!empty(@$data->image))
                          <x-remove-button url="{{ route('user.pages.aboutus.removeImg', ['language_id' => $lang_id]) }}"
                            name="image" type="image"/>
                        @endif

                      </div>
                      <br>
                      <input type="hidden" name="ai_generated_image" id="ai_generated_about_image">
                      <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
                        <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                          {{ __('Choose Image') }}
                          <input type="file" class="img-input" name="image">
                        </div>
                        <button type="button" class="btn btn-info btn-sm" data-ai-image-open
                          data-endpoint="{{ route('user.ai.generate.category.image') }}"
                          data-target="#about_image_preview"
                          data-hidden="#ai_generated_about_image"
                          data-confirm-text="{{ __('Generate Image') }}">
                          <i class="fas fa-magic mr-1"></i> {{ __('Generate Image') }}
                        </button>
                      </div>
                      <p class="text-warning p-0 mb-1">
                        {{ __('Recommended Image size : 720X550') }}
                      </p>
                      @if ($errors->has('image'))
                        <p class="mt-2 mb-0 text-danger">{{ $errors->first('slider_img') }}</p>
                      @endif
                    </div>
                  </div>
                </div>
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
                      <input type="text" value="{{ @$data->title }}" class="form-control" name="title"
                        placeholder="{{ __('Enter title') }}">
                      @error('title')
                        <div class="text-danger">{{ $message }}</div>
                      @enderror
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
                      <input type="text" value="{{ @$data->subtitle }}" class="form-control" name="subtitle"
                        placeholder="{{ __('Enter subtitle') }}">
                      @error('subtitle')
                        <div class="text-danger">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="button_text">{{ __('Button Text') }}</label>
                      <input type="text" value="{{ @$data->button_text }}" class="form-control" name="button_text"
                        placeholder="{{ __('Enter button text') }}">
                      @error('button_text')
                        <div class="text-danger">{{ $message }}</div>
                      @enderror
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label for="button_url">{{ __('Button Url') }}</label>
                      <input type="text" value="{{ @$data->button_url }}" class="form-control" name="button_url"
                        placeholder="{{ __('Enter button url') }}">
                      @error('button_url')
                        <div class="text-danger">{{ $message }}</div>
                      @enderror
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
              <button type="submit" class="btn btn-success" form="about">
                {{ __('Update') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title">{{ __('Features') }}</div>
            </div>

            <div class="col-lg-3">
              @if (!empty($userLanguages))
                <select name="language" class="form-control"
                  onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                  <option value="" selected disabled>
                    {{ __('Select a Language') }}</option>
                  @foreach ($userLanguages as $lang)
                    <option value="{{ $lang->code }}"
                      {{ $lang->code == request()->input('language') ? 'selected' : '' }}>
                      {{ $lang->name }}</option>
                  @endforeach
                </select>
              @endif
            </div>

            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal"
                class="btn btn-primary btn-sm {{ $dashboard_language->rtl == 1 ? 'float-lg-left float-right' : 'float-lg-right float-left' }}"><i
                  class="fas fa-plus"></i>
                {{ __('Add') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('user.pages.about_us.bulk_delete_features') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col">
              @if (count($features) == 0)
                <h3 class="text-center mt-2">{{ __('NO FEATURES FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Icon') }}</th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($features as $feature)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $feature->id }}">
                          </td>
                          <td><i class="{{ $feature->icon }}"></i></td>
                          <td>
                            {{ strlen($feature->title) > 30 ? mb_substr($feature->title, 0, 30, 'UTF-8') . '...' : $feature->title }}
                          </td>
                          <td>{{ $feature->serial_number }}</td>
                          <td>
                            <a class="btn btn-secondary btn-sm mr-1  mt-1 editbtn"
                              href="{{ route('user.pages.about_us.features.edit', $feature->id) }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('user.pages.about_us.delete_features', ['id' => $feature->id]) }}"
                              method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger mt-1 btn-sm deleteBtn">
                                <span class="btn-label">
                                  <i class="fas fa-trash"></i>
                                </span>
                              </button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>
        <div class="card-footer"></div>
      </div>
    </div>
  </div>
  @includeIf('user.about.features.create')
@endsection
@section('scripts')
  <script src="{{ asset('assets/user/js/ai-image-modal.js') }}"></script>
  <script src="{{ asset('assets/user/js/ai-form-generator.js') }}"></script>
  <script>
    "use strict";

    function updateAboutAiLang() {
      var code = $('#about_language option:selected').data('code') || $('#about_language').val() || '';
      $('.ai-field-btn').attr('data-lang', code);
    }

    $(document).on('change', '#about_language', updateAboutAiLang);
    updateAboutAiLang();

    var aboutAiScope = null;
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
      outputs: function () {
        if (!aboutAiScope || !aboutAiScope.length) return {};
        return {
          title: aboutAiScope.find('[name="title"]'),
          subtitle: aboutAiScope.find('[name="subtitle"]')
        };
      },
      inputSuffix: '',

      extra: {
        mode: () => 'home_page_text'
      },
      onOpen: function ($btn) {
        aboutAiScope = $btn.closest('form');
        if (!aboutAiScope.length) {
          aboutAiScope = $btn.closest('.modal');
        }
      }
    });
  </script>
  <script src="{{ asset('assets/user/js/image-text.js') }}"></script>
@endsection

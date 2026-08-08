@extends('user.layout')

@php
  $selLang = \App\Models\User\Language::where([
      ['code', \Illuminate\Support\Facades\Session::get('currentLangCode')],
      ['user_id', \Illuminate\Support\Facades\Auth::id()],
  ])->first();
  $userDefaultLang = \App\Models\User\Language::where([
      ['user_id', \Illuminate\Support\Facades\Auth::id()],
      ['is_default', 1],
  ])->first();
  $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
@endphp
@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('SEO Informations') }}</h4>
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
        <a href="#">{{ __('SEO Informations') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <form action="{{ route('user.basic_settings.update_seo_informations') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="row">
              <div class="col-lg-9">
                <div class="card-title">{{ __('Update SEO Informations') }}</div>
              </div>

              <div class="col-lg-3">
                @if (!is_null($userDefaultLang))
                  @if (!empty($userLanguages))
                    <select name="language" class="form-control float-right"
                      onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                      <option value="" selected disabled>
                        {{ __('Select a Language') }}</option>
                      @foreach ($userLanguages as $lang)
                        <option value="{{ $lang->code }}"
                          {{ $lang->code == request()->input('language') ? 'selected' : '' }}>{{ $lang->name }}
                        </option>
                      @endforeach
                    </select>
                  @endif
                @endif
              </div>
            </div>
          </div>

          <div class="card-body pt-5 pb-5">
            <div class="row">

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Meta Keywords For Home Page') }}</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn" data-field="home_meta_keywords"
                      data-lang="" data-title="{{ __('Meta Keywords For Home Page') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>
                  <input class="form-control" name="home_meta_keywords" value="{{ $data->home_meta_keywords }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Meta Description For Home Page') }}</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn" data-field="home_meta_description"
                      data-lang="" data-title="{{ __('Meta Description For Home Page') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>
                  <textarea class="form-control" name="home_meta_description" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ $data->home_meta_description }}</textarea>
                </div>
              </div>


              <div class="col-lg-6">
                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Meta Keywords For Shop Page') }}</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn" data-field="shop_meta_keywords"
                      data-lang="" data-title="{{ __('Meta Keywords For Shop Page') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>
                  <input class="form-control" name="shop_meta_keywords" value="{{ $data->shop_meta_keywords }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Meta Description For Shop Page') }}</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn" data-field="shop_meta_description"
                      data-lang="" data-title="{{ __('Meta Description For Shop Page') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>
                  <textarea class="form-control" name="shop_meta_description" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ $data->shop_meta_description }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Meta Keywords For blog Page') }}</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn" data-field="blogs_meta_keywords"
                      data-lang="" data-title="{{ __('Meta Keywords For blog Page') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>
                  <input class="form-control" name="blogs_meta_keywords" value="{{ $data->blogs_meta_keywords }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Meta Description For blog Page') }}</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn" data-field="blogs_meta_description"
                      data-lang="" data-title="{{ __('Meta Description For blog Page') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>
                  <textarea class="form-control" name="blogs_meta_description" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ $data->blogs_meta_description }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Meta Keywords For faqs Page') }}</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn" data-field="faqs_meta_keywords"
                      data-lang="" data-title="{{ __('Meta Keywords For faqs Page') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>
                  <input class="form-control" name="faqs_meta_keywords" value="{{ $data->faqs_meta_keywords }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Meta Description For faqs Page') }}</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn" data-field="faqs_meta_description"
                      data-lang="" data-title="{{ __('Meta Description For faqs Page') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>
                  <textarea class="form-control" name="faqs_meta_description" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ $data->faqs_meta_description }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Meta Keywords For contact Page') }}</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn" data-field="contact_meta_keywords"
                      data-lang="" data-title="{{ __('Meta Keywords For contact Page') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>

                  <input class="form-control" name="contact_meta_keywords" value="{{ $data->contact_meta_keywords }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Meta Description For contact Page') }}</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                      data-field="contact_meta_description" data-lang=""
                      data-title="{{ __('Meta Description For contact Page') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>
                  <textarea class="form-control" name="contact_meta_description" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ $data->contact_meta_description }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Meta Keywords For About Page') }}</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                      data-field="about_page_meta_keywords" data-lang=""
                      data-title="{{ __('Meta Keywords For About Page') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>

                  <input class="form-control" name="about_page_meta_keywords"
                    value="{{ $data->about_page_meta_keywords }}" placeholder="{{ __('Enter Meta Keywords') }}"
                    data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Meta Description For About Page') }}</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                      data-field="about_page_meta_description" data-lang=""
                      data-title="{{ __('Meta Description For About Page') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>
                  <textarea class="form-control" name="about_page_meta_description" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ $data->about_page_meta_description }}</textarea>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Meta Keywords For login Page') }}</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn" data-field="login_meta_keywords"
                      data-lang="" data-title="{{ __('Meta Keywords For login Page') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>

                  <input class="form-control" name="login_meta_keywords" value="{{ $data->login_meta_keywords }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Meta Description For login Page') }}</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                      data-field="login_meta_description" data-lang=""
                      data-title="{{ __('Meta Description For login Page') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>
                  <textarea class="form-control" name="login_meta_description" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ $data->login_meta_description }}</textarea>
                </div>

              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Meta Keywords For signup Page') }}</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                      data-field="signup_meta_keywords" data-lang=""
                      data-title="{{ __('Meta Keywords For signup Page') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>

                  <input class="form-control" name="signup_meta_keywords" value="{{ $data->signup_meta_keywords }}"
                    placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Meta Description For signup Page') }}</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                      data-field="signup_meta_description" data-lang=""
                      data-title="{{ __('Meta Description For signup Page') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>
                  <textarea class="form-control" name="signup_meta_description" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ $data->signup_meta_description }}</textarea>
                </div>

              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Meta Description For forget password Page') }}</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                      data-field="forget_password_meta_keywords" data-lang=""
                      data-title="{{ __('Meta Description For forget password Page') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>

                  <input class="form-control" name="forget_password_meta_keywords"
                    value="{{ $data->forget_password_meta_keywords }}" placeholder="{{ __('Enter Meta Keywords') }}"
                    data-role="tagsinput">
                </div>

                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Meta Description For forget password Page') }}</span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                      data-field="forget_password_meta_description" data-lang=""
                      data-title="{{ __('Meta Description For forget password Page') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>
                  <textarea class="form-control" name="forget_password_meta_description" rows="5"
                    placeholder="{{ __('Enter Meta Description') }}">{{ $data->forget_password_meta_description }}</textarea>
                </div>

              </div>


              @if (count($pages) > 0)
                @foreach ($pages as $page)
                  @php
                    $pageContent = App\Models\User\UserPageContent::where([
                        ['page_id', $page->id],
                        ['user_id', Auth::id()],
                    ])
                        ->where('language_id', $language->id)
                        ->first();
                  @endphp
                  <div class="col-lg-6">
                    <div class="form-group">
                      <label class="d-flex align-items-center justify-content-between">
                        <span>{{ __('Meta Keywords For') . ' ' . @$pageContent->title . ' ' . __('Page') }}</span>
                        <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                          data-field="custome_page_meta_keyword_{{ $page->id }}" data-lang=""
                          data-title="{{ __('Meta Keywords For') . ' ' . @$pageContent->title . ' ' . __('Page') }}">
                          <i class="fas fa-magic"></i> {{ __('Generate') }}
                        </button>
                      </label>
                      <input class="form-control" name="custome_page_meta_keyword[{{ $page->id }}]"
                        value="{{ isset($decodedKeywords[$page->id]) ? $decodedKeywords[$page->id] : '' }}"
                        placeholder="{{ __('Enter Meta Keywords') }}" data-role="tagsinput">
                    </div>

                    <div class="form-group">
                      <label class="d-flex align-items-center justify-content-between">
                        <span>{{ __('Meta Description For') . ' ' . @$pageContent->title . ' ' . __('Page') }}</span>
                        <button type="button" class="btn btn-sm btn-primary ai-field-btn"
                          data-field="custome_page_meta_description_{{ $page->id }}" data-lang=""
                          data-title="{{ __('Meta Description For') . ' ' . @$pageContent->title . ' ' . __('Page') }}">
                          <i class="fas fa-magic"></i> {{ __('Generate') }}
                        </button>
                      </label>
                      <textarea class="form-control" name="custome_page_meta_description[{{ $page->id }}]" rows="5"
                        placeholder="{{ __('Enter Meta Description') }}">{{ isset($decodedDescriptions[$page->id]) ? $decodedDescriptions[$page->id] : '' }}</textarea>
                    </div>
                  </div>
                @endforeach
              @endif
            </div>
          </div>

          <div class="card-footer">
            <div class="form">
              <div class="row">
                <div class="col-12 text-center">
                  <button type="submit"
                    class="btn btn-success {{ $data == null ? 'd-none' : '' }}">{{ __('Update') }}</button>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="{{ asset('assets/user/js/ai-form-generator.js') }}"></script>
  <script>
    "use strict";
    var seoAiKey = '';
    var seoAiTarget = null;

    function updateSeoAiLang() {
      var code = $('select[name="language"]').val() || '';
      $('.ai-field-btn').attr('data-lang', code);
    }

    $(document).on('change', 'select[name="language"]', updateSeoAiLang);
    updateSeoAiLang();

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
        if (!seoAiKey || !seoAiTarget || !seoAiTarget.length) return {};
        var out = {};
        out[seoAiKey] = seoAiTarget;
        return out;
      },
      inputSuffix: '',

      extra: {
        mode: () => 'home_page_text'
      },
      onOpen: function($btn) {
        seoAiKey = ($btn.data('field') || '').toString().trim();
        seoAiTarget = $btn.closest('.form-group').find('input, textarea').first();
      }
    });
  </script>
@endsection

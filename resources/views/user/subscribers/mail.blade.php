@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Mail to subscribers') }}</h4>
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
        <a href="#">{{ __('Subscribers') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Mail to subscribers') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card">
        <form action="{{ route('user.subscribers.sendmail') }}" method="post">
          @csrf
          <div class="card-header">
            <div class="card-title">{{ __('Mail to subscribers') }}</div>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-lg-8 offset-lg-2">
                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Subject') }} <span class="text-danger">**</span></span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn" data-field="subject"
                      data-lang="" data-title="{{ __('Subject') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>
                  <input type="text" class="form-control" name="subject" value=""
                    placeholder="{{ __('Enter subject of E-mail') }}">
                  @if ($errors->has('subject'))
                    <p class="text-danger mb-0">{{ $errors->first('subject') }}</p>
                  @endif
                </div>
                <div class="form-group">
                  <label class="d-flex align-items-center justify-content-between">
                    <span>{{ __('Message') }} <span class="text-danger">**</span></span>
                    <button type="button" class="btn btn-sm btn-primary ai-field-btn" data-field="message"
                      data-lang="" data-title="{{ __('Message') }}">
                      <i class="fas fa-magic"></i> {{ __('Generate') }}
                    </button>
                  </label>
                  <textarea name="message" class="form-control summernote" data-height="150"></textarea>
                  @if ($errors->has('message'))
                    <p class="text-danger mb-0">{{ $errors->first('message') }}</p>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer text-center">
            <button type="submit" class="btn btn-success">
              <span class="btn-label">
                <i class="fa fa-check"></i>
              </span>
              {{ __('Send Mail') }}
            </button>
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
    var mailAiKey = '';
    var mailAiTarget = null;

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
        if (!mailAiKey || !mailAiTarget || !mailAiTarget.length) return {};
        var out = {};
        out[mailAiKey] = mailAiTarget;
        return out;
      },
      inputSuffix: '',

      extra: {
        mode: () => 'mail'
      },
      onOpen: function($btn) {
        mailAiKey = ($btn.data('field') || '').toString().trim();
        mailAiTarget = $btn.closest('.form-group').find('input, textarea').first();
      }
    });
  </script>
@endsection

@extends('admin.layout')

@section('styles')
  <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-iconpicker.min.css') }}">
@endsection

@php
  $selLang = \App\Models\Language::where('code', request()->input('language'))->first();
@endphp
@if (!empty($selLang) && $selLang->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
  @endsection
@endif

@section('content')
  <div class="page-header d-flex align-items-center justify-content-between">
    <div>
      <h4 class="page-title d-flex align-items-center gap-2">
        {{ __('Menu Builder') }}
        <i class="fas fa-home text-muted" style="font-size: 1rem;"></i>
      </h4>
      <ul class="breadcrumbs m-0">
        <li class="nav-home">
          <a href="{{ route('admin.dashboard') }}">
            <i class="fas fa-home"></i>
          </a>
        </li>
        <li class="separator">
          <i class="fas fa-chevron-right"></i>
        </li>
        <li class="nav-item">
          <a href="#">{{ __('Menu Builder') }}</a>
        </li>
      </ul>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card border-0" style="border-radius: 20px; box-shadow: var(--shadow-card);">
        <div class="card-header border-0 pb-0 pt-4 px-4">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <h3 class="card-title m-0 font-weight-bold" style="font-size: 1.25rem;">{{ __('Menu Builder') }}</h3>
            <div style="min-width: 160px;">
              @include('admin.partials.languages')
            </div>
          </div>
        </div>

        <div class="card-body p-4">
          <div class="row">

            {{-- Column 1: Available Pages --}}
            <div class="col-lg-4 mb-4 mb-lg-0">
              <div class="mb-card-inner">
                <div class="mb-col-header">
                  <i class="la flaticon-file mr-2"></i> {{ __('Available Pages') }}
                </div>
                <div class="mb-col-body menu-builder-list-area">
                  <ul class="list-unstyled m-0 p-0">
                    <li class="mb-page-item">
                      <span class="mb-page-name"><i class="far fa-file-alt mr-2 text-muted"></i>{{ __('Home') }}</span>
                      <a data-text="{{ __('Home') }}" data-type="home"
                        class="addToMenus btn-mb-add" href="">{{ __('Add to Menus') }}</a>
                    </li>
                    <li class="mb-page-item">
                      <span class="mb-page-name"><i class="far fa-file-alt mr-2 text-muted"></i>{{ __('Shops') }}</span>
                      <a data-text="{{ __('Shops') }}" data-type="listings"
                        class="addToMenus btn-mb-add" href="listings">{{ __('Add to Menus') }}</a>
                    </li>
                    <li class="mb-page-item">
                      <span class="mb-page-name"><i class="far fa-file-alt mr-2 text-muted"></i>{{ __('Pricing') }}</span>
                      <a data-text="{{ __('Pricing') }}" data-type="pricing"
                        class="addToMenus btn-mb-add" href="">{{ __('Add to Menus') }}</a>
                    </li>
                    <li class="mb-page-item">
                      <span class="mb-page-name"><i class="far fa-file-alt mr-2 text-muted"></i>{{ __('Templates') }}</span>
                      <a data-text="{{ __('Templates') }}" data-type="templates"
                        class="addToMenus btn-mb-add" href="">{{ __('Add to Menus') }}</a>
                    </li>
                    <li class="mb-page-item">
                      <span class="mb-page-name"><i class="far fa-file-alt mr-2 text-muted"></i>{{ __('Blogs') }}</span>
                      <a data-text="{{ __('Blogs') }}" data-type="blog"
                        class="addToMenus btn-mb-add" href="">{{ __('Add to Menus') }}</a>
                    </li>
                    <li class="mb-page-item">
                      <span class="mb-page-name"><i class="far fa-file-alt mr-2 text-muted"></i>{{ __('FAQs') }}</span>
                      <a data-text="{{ __('FAQs') }}" data-type="faq"
                        class="addToMenus btn-mb-add" href="">{{ __('Add to Menus') }}</a>
                    </li>
                    <li class="mb-page-item">
                      <span class="mb-page-name"><i class="far fa-file-alt mr-2 text-muted"></i>{{ __('About') }}</span>
                      <a data-text="{{ __('About') }}" data-type="about"
                        class="addToMenus btn-mb-add" href="">{{ __('Add to Menus') }}</a>
                    </li>
                    <li class="mb-page-item">
                      <span class="mb-page-name"><i class="far fa-file-alt mr-2 text-muted"></i>{{ __('Contact') }}</span>
                      <a data-text="{{ __('Contact') }}" data-type="contact"
                        class="addToMenus btn-mb-add" href="">{{ __('Add to Menus') }}</a>
                    </li>
                    @foreach ($pages as $page)
                      <li class="mb-page-item">
                        <span class="mb-page-name">
                          <i class="far fa-file-alt mr-2 text-muted"></i>{{ $page->title }}
                          <span class="badge badge-pill" style="background: #EEF2FF; color: #6366F1; font-size: 0.65rem; font-weight: 600; padding: 2px 7px; margin-left: 4px; border-radius: 20px;">{{ __('Additional Page') }}</span>
                        </span>
                        <a data-text="{{ $page->title }}" data-type="{{ $page->id }}" data-custom="yes"
                          class="addToMenus btn-mb-add" href="">{{ __('Add to Menus') }}</a>
                      </li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>

            {{-- Column 2: Menu Item Details --}}
            <div class="col-lg-4 mb-4 mb-lg-0">
              <div class="mb-card-inner d-flex flex-column" style="height: 100%;">
                <div class="mb-col-header">
                  <i class="far fa-edit mr-2"></i> {{ __('Menu Item Details') }}
                </div>
                <div class="mb-col-body flex-grow-1">
                  <form id="frmEdit" class="form-horizontal">
                    <input class="item-menu" type="hidden" name="type" value="">

                    <div id="withUrl">
                      <div class="form-group mb-3">
                        <label class="mb-label">{{ __('Text') }}</label>
                        <input type="text" class="form-control mb-input item-menu" name="text"
                          placeholder="{{ __('Text') }}">
                      </div>
                      <div class="form-group mb-3">
                        <label class="mb-label">{{ __('URL') }}</label>
                        <input type="text" class="form-control mb-input item-menu" name="href"
                          placeholder="{{ __('URL') }}">
                      </div>
                      <div class="form-group mb-3">
                        <label class="mb-label">{{ __('Target') }}</label>
                        <select name="target" id="target" class="form-control mb-input item-menu">
                          <option value="_self">{{ __('Self') }}</option>
                          <option value="_blank">{{ __('Blank') }}</option>
                          <option value="_top">{{ __('Top') }}</option>
                        </select>
                      </div>
                    </div>

                    @php
                      $noneAttr = 'none';
                    @endphp
                    <div id="withoutUrl" style="display: {{ $noneAttr }}">
                      <div class="form-group mb-3">
                        <label class="mb-label">{{ __('Text') }}</label>
                        <input type="text" class="form-control mb-input item-menu" name="text"
                          placeholder="{{ __('Text') }}">
                      </div>
                      <div class="form-group mb-3">
                        <label class="mb-label">{{ __('URL') }}</label>
                        <input type="text" class="form-control mb-input item-menu" name="href"
                          placeholder="{{ __('URL') }}">
                      </div>
                      <div class="form-group mb-3">
                        <label class="mb-label">{{ __('Target') }}</label>
                        <select name="target" class="form-control mb-input item-menu">
                          <option value="_self">{{ __('Self') }}</option>
                          <option value="_blank">{{ __('Blank') }}</option>
                          <option value="_top">{{ __('Top') }}</option>
                        </select>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="mb-col-footer">
                  <button type="button" id="btnUpdate" class="btn-mb-action btn-mb-update" disabled>
                    <i class="fas fa-sync-alt mr-1"></i> {{ __('Update') }}
                  </button>
                  <button type="button" id="btnAdd" class="btn-mb-action btn-mb-add-action">
                    <i class="fas fa-plus mr-1"></i> {{ __('Add') }}
                  </button>
                </div>
              </div>
            </div>

            {{-- Column 3: Menu Structure --}}
            <div class="col-lg-4">
              <div class="mb-card-inner">
                <div class="mb-col-header">
                  <i class="fas fa-list-ul mr-2"></i> {{ __('Menu Structure') }}
                </div>
                <div class="mb-col-body">
                  <ul id="myEditor" class="sortableLists list-group mb-structure-list"></ul>
                </div>
              </div>
            </div>

          </div>
        </div>

        <div class="card-footer border-0 bg-transparent pb-4 pt-0 text-center">
          <button id="btnOutput" class="btn-mb-update-menu">
            <i class="fas fa-calendar-check mr-2"></i> {{ __('Update Menu') }}
          </button>
        </div>
      </div>
    </div>
  </div>
@endsection



@section('scripts')
  <script type="text/javascript" src="{{ asset('assets/admin/js/plugin/jquery-menu-editor/jquery-menu-editor.js') }}">
  </script>
  <script>
    "use strict";
    var prevMenus = {!! json_encode($prevMenu) !!};
    var langid = "{{ $lang_id }}";
    var menuUpdate = "{{ route('admin.menu_builder.update') }}";
  </script>
  <script type="text/javascript" src="{{ asset('assets/admin/js/menu-builder.js') }}"></script>
@endsection

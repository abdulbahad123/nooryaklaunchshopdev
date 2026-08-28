@extends('admin.layout')

@section('content')
  <div class="page-header d-flex align-items-center justify-content-between">
    <div>
      <h4 class="page-title d-flex align-items-center gap-2">
        {{ __('Languages') }}
        <i class="fas fa-home text-muted" style="font-size: 1rem;"></i>
      </h4>
      <ul class="breadcrumbs m-0">
        <li class="nav-home">
          <a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i></a>
        </li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="nav-item"><a href="#">{{ __('Settings') }}</a></li>
        <li class="separator"><i class="fas fa-chevron-right"></i></li>
        <li class="nav-item"><a href="#">{{ __('Languages') }}</a></li>
      </ul>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card border-0" style="border-radius: 20px; box-shadow: var(--shadow-card);">
        <div class="card-header border-0 pb-0 pt-4 px-4">
          <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <h3 class="card-title m-0 font-weight-bold" style="font-size: 1.25rem;">{{ __('Languages') }}</h3>
            <div class="d-flex align-items-center gap-2 flex-wrap language_relateBtn">
              <a href="#" class="btn-lang-action btn-lang-outline" data-toggle="modal" data-target="#addModal">
                <i class="fas fa-plus"></i> {{ __('Add Frontend Keyword') }}
              </a>
              <a href="#" class="btn-lang-action btn-lang-outline" data-toggle="modal" data-target="#addAdminKeywordModal">
                <i class="fas fa-plus"></i> {{ __('Add Admin Keyword') }}
              </a>
              <a href="#" class="btn-lang-action btn-lang-primary" data-toggle="modal" data-target="#createModal">
                <i class="fas fa-plus"></i> {{ __('Add Language') }}
              </a>
            </div>
          </div>
        </div>

        <div class="card-body p-4">
          @if (count($languages) == 0)
            <div class="text-center py-5 text-muted">
              <i class="fas fa-language" style="font-size: 48px; opacity: 0.4;"></i>
              <h4 class="mt-3 font-weight-bold">{{ __('NO LANGUAGE FOUND') }}</h4>
            </div>
          @else
            <div class="table-responsive" style="overflow-x: auto; width: 100%;">
              <table class="table table-hover align-middle" style="white-space: nowrap;">
                <thead>
                  <tr>
                    <th scope="col">{{ __('Name') }}</th>
                    <th scope="col">{{ __('Code') }}</th>
                    <th scope="col">{{ __('Default in Website') }}</th>
                    <th scope="col">{{ __('Default in Dashboard') }}</th>
                    <th scope="col" class="text-right">{{ __('Actions') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($languages as $key => $language)
                    <tr>
                      <td>
                        <div class="d-flex align-items-center gap-2">
                          <div class="lang-icon-badge">
                            @if ($language->code == 'ar')
                              <span style="font-family: serif; font-size: 0.9rem;">ع</span>
                            @else
                              <i class="fas fa-globe"></i>
                            @endif
                          </div>
                          <span class="font-weight-600" style="font-size: 0.875rem;">{{ $language->name }}</span>
                        </div>
                      </td>
                      <td>
                        <span class="lang-code-badge">{{ $language->code }}</span>
                      </td>
                      <td>
                        @if ($language->is_default == 1)
                          <span class="lang-status-badge lang-status-default">{{ __('Default') }}</span>
                        @else
                          <form class="d-inline-block m-0" action="{{ route('admin.language.default', $language->id) }}" method="post">
                            @csrf
                            <button class="lang-status-badge lang-status-make-default" type="submit" name="button">
                              {{ __('Make Default') }}
                            </button>
                          </form>
                        @endif
                      </td>
                      <td>
                        @if ($language->dashboard_default == 1)
                          <span class="lang-status-badge lang-status-default">{{ __('Default') }}</span>
                        @else
                          <form class="d-inline-block m-0" action="{{ route('admin.language.dashboardDefault', $language->id) }}" method="post">
                            @csrf
                            <button class="lang-status-badge lang-status-make-default" type="submit" name="button">
                              {{ __('Make Default') }}
                            </button>
                          </form>
                        @endif
                      </td>
                      <td class="text-right">
                        <div class="d-inline-flex align-items-center gap-2">
                          <div class="dropdown">
                            <button class="btn-lang-select dropdown-toggle" type="button"
                              data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              {{ __('Select') }} <i class="fas fa-chevron-down ml-1" style="font-size: 0.7rem;"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right lang-dropdown-menu">
                              <a href="{{ route('admin.language.edit', $language->id) }}" class="dropdown-item lang-dropdown-item">
                                {{ __('Edit') }}
                              </a>
                              <a class="dropdown-item lang-dropdown-item" href="{{ route('admin.language.editKeyword', $language->id) }}">
                                {{ __('Edit Admin Frontend Keywords') }}
                              </a>
                              <a class="dropdown-item lang-dropdown-item" href="{{ route('admin.language.admin_dashboard.editKeyword', $language->id) }}">
                                {{ __('Edit Admin Dashboard Keywords') }}
                              </a>
                              <a class="dropdown-item lang-dropdown-item" href="{{ route('admin.language.user_dashboard.editKeyword', $language->id) }}">
                                {{ __('Edit User Dashboard Keywords') }}
                              </a>
                              <a class="dropdown-item lang-dropdown-item" href="{{ route('admin.language.user_frontend.editKeyword', $language->id) }}">
                                {{ __('Edit User Frontend Keywords') }}
                              </a>
                              <div class="dropdown-divider" style="border-color: var(--border-card);"></div>
                              <form class="deleteform m-0" action="{{ route('admin.language.delete', $language->id) }}" method="post">
                                @csrf
                                <button type="submit" class="dropdown-item lang-dropdown-item text-danger deletebtn">
                                  {{ __('Delete') }}
                                </button>
                              </form>
                            </div>
                          </div>
                          {{-- three dots menu --}}
                          <div class="dropdown">
                            <button class="lang-dots-btn" type="button" data-toggle="dropdown">
                              <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right lang-dropdown-menu">
                              <a href="{{ route('admin.language.edit', $language->id) }}" class="dropdown-item lang-dropdown-item">
                                {{ __('Edit Language') }}
                              </a>
                            </div>
                          </div>
                        </div>
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
  </div>

  {{-- Add Frontend Keyword Modal --}}
  <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: var(--shadow-card);">
        <div class="modal-header border-0 pb-0 pt-4 px-4">
          <h5 class="modal-title font-weight-bold">{{ __('Add Keyword') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4">
          <form id="ajaxForm2" action="{{ route('admin.language.add_keyword') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
              <label class="popup-field-label">{{ __('Keyword') }} <span class="text-danger">**</span></label>
              <input type="text" class="form-control popup-field-input" name="keyword" placeholder="{{ __('Enter Keyword') }}">
              <p id="errkeyword" class="mt-1 mb-0 text-danger em small"></p>
            </div>
          </form>
        </div>
        <div class="modal-footer border-0 pt-0 pb-4 px-4">
          <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ __('Close') }}</button>
          <button id="submitBtn2" type="button" class="btn-primary-purple py-2 px-4" style="border-radius: 10px;">{{ __('Submit') }}</button>
        </div>
      </div>
    </div>
  </div>

  {{-- Add Admin Keyword Modal --}}
  <div class="modal fade" id="addAdminKeywordModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: var(--shadow-card);">
        <div class="modal-header border-0 pb-0 pt-4 px-4">
          <h5 class="modal-title font-weight-bold">{{ __('Add Keyword') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4">
          <form id="ajaxForm3" action="{{ route('admin.language.add_keyword.admin.dashboard') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
              <label class="popup-field-label">{{ __('Keyword') }} <span class="text-danger">**</span></label>
              <input type="text" class="form-control popup-field-input" name="keyword" placeholder="{{ __('Enter Keyword') }}">
              <p id="errrkeyword" class="mt-1 mb-0 text-danger em small"></p>
            </div>
          </form>
        </div>
        <div class="modal-footer border-0 pt-0 pb-4 px-4">
          <button type="button" class="btn btn-light rounded-pill px-4" data-dismiss="modal">{{ __('Close') }}</button>
          <button id="submitBtn3" type="button" class="btn-primary-purple py-2 px-4" style="border-radius: 10px;">{{ __('Submit') }}</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Create Language Modal -->
  @includeif('admin.language.create')
@endsection

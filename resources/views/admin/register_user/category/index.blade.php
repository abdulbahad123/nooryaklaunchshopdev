@extends('admin.layout')

@php
    $selLang = $lang ?? \App\Models\Language::where('code', request()->input('language'))->first() ?? \App\Models\Language::where('is_default', 1)->first();
    $categoriesList = $bcategories ?? $categories ?? [];
@endphp

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Categories') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
          <i class="fas fa-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="fas fa-chevron-right"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Users Management') }}</a>
      </li>
      <li class="separator">
        <i class="fas fa-chevron-right"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Categories') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <!-- Card Header -->
        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
          <div class="card-title m-0">
            <span class="cat-icon-badge i-purple m-0" style="width:36px; height:36px; font-size:0.95rem;">
              <i class="fas fa-list-alt"></i>
            </span>
            {{ __('Categories') }}
          </div>
          <div class="d-flex align-items-center gap-3">
            @if (!empty($langs))
              <select name="language" class="form-control form-control-sm" onchange="window.location.href='{{ url()->current() }}?language=' + this.value" style="min-width: 140px;">
                @foreach ($langs as $l)
                  <option value="{{ $l->code }}" {{ $l->code == ($selLang ? $selLang->code : 'en') ? 'selected' : '' }}>
                    🌐 {{ $l->name }}
                  </option>
                @endforeach
              </select>
            @endif

            <a href="#" class="btn-primary-purple" data-toggle="modal" data-target="#createModal" style="white-space: nowrap !important; display: inline-flex; align-items: center; gap: 6px;">
              <i class="fas fa-plus"></i>
              <span>{{ __('Add Category') }}</span>
            </a>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (empty($categoriesList) || count($categoriesList) == 0)
                <h3 class="text-center py-4 text-muted">{{ __('NO CATEGORY FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped align-middle">
                    <thead>
                      <tr>
                        <th scope="col" style="width: 60px;">#</th>
                        <th scope="col">{{ __('Name') }}</th>
                        <th scope="col">{{ __('Status') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col" class="text-right" style="width: 140px;">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($categoriesList as $key => $category)
                        @php
                            $cName = strtolower($category->name);
                            $badgeClass = 'i-purple';
                            $iconClass = 'fas fa-tag';
                            if (str_contains($cName, 'jewel')) { $badgeClass = 'i-purple'; $iconClass = 'fas fa-gem'; }
                            elseif (str_contains($cName, 'skin') || str_contains($cName, 'cosmetic')) { $badgeClass = 'i-blue'; $iconClass = 'fas fa-pump-soap'; }
                            elseif (str_contains($cName, 'pet') || str_contains($cName, 'dog')) { $badgeClass = 'i-pink'; $iconClass = 'fas fa-paw'; }
                            elseif (str_contains($cName, 'electr') || str_contains($cName, 'tech')) { $badgeClass = 'i-yellow'; $iconClass = 'fas fa-laptop'; }
                            elseif (str_contains($cName, 'groc') || str_contains($cName, 'food')) { $badgeClass = 'i-green'; $iconClass = 'fas fa-shopping-cart'; }
                            elseif (str_contains($cName, 'furnit') || str_contains($cName, 'home')) { $badgeClass = 'i-blue'; $iconClass = 'fas fa-couch'; }
                        @endphp
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>
                            <div class="d-flex align-items-center">
                              <span class="cat-icon-badge {{ $badgeClass }}">
                                <i class="{{ $iconClass }}"></i>
                              </span>
                              <span class="font-weight-bold">{{ $category->name }}</span>
                            </div>
                          </td>
                          <td>
                            @if ($category->status == 1)
                              <span class="status-pill-active">
                                <i class="fas fa-circle" style="font-size:0.5rem;"></i> {{ __('Active') }}
                              </span>
                            @else
                              <span class="status-pill-deactive">
                                <i class="fas fa-circle" style="font-size:0.5rem;"></i> {{ __('Deactive') }}
                              </span>
                            @endif
                          </td>
                          <td>
                            <span class="font-weight-bold text-muted">{{ $category->serial_number }}</span>
                          </td>
                          <td class="text-right">
                            <div class="d-inline-flex align-items-center gap-2">
                              <a class="btn-action-square b-edit"
                                href="{{ route('register.user.category_edit', $category->id) . '?language=' . ($selLang ? $selLang->code : 'en') }}"
                                title="{{ __('Edit') }}">
                                <i class="fas fa-pencil-alt"></i>
                              </a>
                              <form class="deleteform d-inline-block"
                                action="{{ route('register.user.category_delete') }}" method="post">
                                @csrf
                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                <button type="submit" class="btn-action-square b-delete deletebtn" title="{{ __('Delete') }}">
                                  <i class="fas fa-trash"></i>
                                </button>
                              </form>
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
    </div>
  </div>

  @includeIf('admin.register_user.category.create')
@endsection

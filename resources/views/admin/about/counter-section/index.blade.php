@extends('admin.layout')

@if (!empty($abe->language) && $abe->language->rtl == 1)
  @section('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
  @endsection
@endif

@section('styles')
<style>
  .hero-editor-tabs .nav-link {
    font-weight: 600;
    padding: 12px 20px;
    border-radius: 10px !important;
    color: #495057;
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    margin-right: 8px;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.25s ease;
  }
  .hero-editor-tabs .nav-link:hover {
    background: #eef2f7;
    color: #0b5ed7;
  }
  .hero-editor-tabs .nav-link.active {
    background: linear-gradient(135deg, #ff5a2c 0%, #d4380d 100%) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(255, 90, 44, 0.3);
    border-color: #d4380d;
  }
</style>
@endsection

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Counter Section') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('admin.dashboard') }}">
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
        <a href="#">{{ __('Counter Section') }}</a>
      </li>
    </ul>
  </div>
    {{-- Counter Tabs --}}
  <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
    <div class="card-body p-3">
      <ul class="nav nav-pills hero-editor-tabs m-0" id="counterTabs" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="tab-home-counter-btn" data-toggle="pill" href="#tab-home-counter" role="tab" aria-controls="tab-home-counter" aria-selected="true">
            <i class="fas fa-home text-primary"></i>
            <span>{{ __('Home Page Counters') }}</span>
          </a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="tab-shops-counter-btn" data-toggle="pill" href="#tab-shops-counter" role="tab" aria-controls="tab-shops-counter" aria-selected="false">
            <i class="fas fa-store text-success"></i>
            <span>{{ __('Stores Launched Counters (/shops)') }}</span>
          </a>
        </li>
      </ul>
    </div>
  </div>

  <div class="tab-content" id="counterTabsContent">
    <div class="tab-pane fade show active" id="tab-home-counter" role="tabpanel" aria-labelledby="tab-home-counter-btn"><div class="row">
    <div class="col-md-6">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col-md-9">
                  <div class="card-title">{{ __('Update Counter Section Information') }}</div>
                </div>
                <div class="col-lg-3">
                  @include('admin.partials.languages')
                </div>
              </div>
            </div>

            <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  <form id="counterImgForm" action="{{ route('admin.home_page.update_counter_section_info') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="lang_code" value="{{ request()->input('language') }}">
                    <div class="form-group">
                      <div class="mb-2">
                        <label for="image"><strong>{{ __('Image') }}</strong></label>
                      </div>
                      <div class="showImage mb-3">
                        <img
                          src="{{ @$counterSection->image ? asset('assets/front/img/counter-section/' . $counterSection->image) : asset('assets/admin/img/noimage.jpg') }}"
                          alt="..." class="img-thumbnail">
                        @if (!empty(@$counterSection->image))
                          <x-remove-button
                            url="{{ route('admin.home_page.counter-section-removeImg', ['language_id' => $language->id]) }}"
                            name="image" type="image"/>
                        @endif
                      </div>
                      <br>
                      <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                        {{ __('Choose Image') }}
                        <input type="file" class="img-input" name="image">
                      </div>
                      @error('image')
                        <p class="mb-0 text-danger">{{ $errors->first('image') }}</p>
                      @enderror
                    </div>

                    <div class="form-group">
                      <label for="">{{ __('Section Title') }} <span class="text-danger">**</span></label>
                      <input type="text" name="title" class="form-control" placeholder="{{ __('Enter Title') }}"
                        value="{{ @$counterSection->title }}">
                      @error('title')
                        <p class="mt-2 mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>

                    <div class="form-group">
                      <label for="">{{ __('Section Text') }} <span class="text-danger">**</span></label>
                      <textarea name="text" class="form-control" placeholder="{{ __('Enter Sub Title') }}">{{ @$counterSection->text }}</textarea>

                      @error('text')
                        <p class="mt-2
                        mb-0 text-danger">{{ $message }}</p>
                      @enderror
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <div class="card-footer">
              <div class="row">
                <div class="col-12 text-center">
                  <button type="submit" form="counterImgForm" class="btn btn-success">
                    {{ __('Update') }}
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-5">
              <div class="card-title">{{ __('Counter Informations') }}</div>
            </div>
            <div class="col-lg-3">
              @include('admin.partials.languages')
            </div>
            <div class="col-lg-4 mt-2 mt-lg-0">
              <a href="#" data-toggle="modal" data-target="#createModal"
                class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i>
                {{ __('Add') }}</a>

              <button class="btn btn-danger btn-sm float-right mr-2 d-none bulk-delete"
                data-href="{{ route('admin.home_page.bulk_delete_counter') }}">
                <i class="flaticon-interface-5"></i> {{ __('Delete') }}
              </button>
            </div>
          </div>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col">
              @if (count($counters) == 0)
                <h3 class="text-center mt-2">{{ __('NO INFORMATION FOUND') . '!' }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col">{{ __('Icon') }}</th>
                        <th scope="col">{{ __('Amount') }}</th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($counters as $counter)
                        <tr>
                          <td>
                            <input type="checkbox" class="bulk-check" data-val="{{ $counter->id }}">
                          </td>
                          <td><i class="{{ $counter->icon }}"></i></td>
                          <td>{{ $counter->amount }}</td>
                          <td>
                            {{ strlen($counter->title) > 20 ? mb_substr($counter->title, 0, 20, 'UTF-8') . '...' : $counter->title }}
                          </td>
                          <td>
                            <a class="btn btn-secondary btn-sm mr-1  mt-1 editBtn" href="#" data-toggle="modal"
                              data-target="#editModal" data-id="{{ $counter->id }}" data-icon="{{ $counter->icon }}"
                              data-amount="{{ $counter->amount }}" data-color="{{ $counter->color }}"
                              data-title="{{ $counter->title }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>
                            </a>

                            <form class="deleteForm d-inline-block"
                              action="{{ route('admin.home_page.delete_counter', ['id' => $counter->id]) }}"
                              method="post">
                              @csrf
                              <button type="submit" class="btn btn-danger btn-sm  mt-1 deleteBtn">
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
      </div>

    {{-- TAB 2: STORES LAUNCHED COUNTERS (/shops) --}}
    <div class="tab-pane fade" id="tab-shops-counter" role="tabpanel" aria-labelledby="tab-shops-counter-btn">
      <div class="card border-0 shadow-sm" style="border-radius: 14px;">
        <div class="card-header bg-white d-flex flex-wrap align-items-center justify-content-between gap-2 py-3">
          <div>
            <h5 class="font-weight-bold mb-1 text-dark">
              <i class="fas fa-store text-success mr-1"></i> {{ __('Stores Launched Page Counter Stats (/shops)') }}
            </h5>
            <small class="text-muted">{{ __('Configure the 4 counter stats shown under the hero on the Stores Launched page.') }}</small>
          </div>
          <div class="d-flex align-items-center gap-2">
            <a href="{{ url('/shops') }}" target="_blank" class="btn btn-outline-primary btn-sm mr-2">
              <i class="fas fa-external-link-alt mr-1"></i> {{ __('View Stores Page') }}
            </a>
            @include('admin.partials.languages')
          </div>
        </div>
        <div class="card-body p-4">
          <form action="{{ route('admin.home_page.update_shops_counters', ['lang_id' => $lang_id ?? 1]) }}" method="POST">
            @csrf
            <div class="row">
              {{-- Stat 1 --}}
              <div class="col-lg-6 col-md-6 mb-4">
                <div class="card border h-100" style="border-radius: 12px; background: #fafbfe;">
                  <div class="card-header bg-light py-2 d-flex align-items-center justify-content-between">
                    <span class="font-weight-bold text-dark"><i class="fas fa-store text-warning mr-1"></i> {{ __('Stat 1 (Stores Launched)') }}</span>
                    <span class="badge badge-warning text-white">{{ __('Position 1') }}</span>
                  </div>
                  <div class="card-body p-3">
                    <div class="form-group p-0 mb-3">
                      <label class="font-weight-bold">{{ __('Amount / Count Value') }}</label>
                      <input type="text" name="shops_stat1_value" class="form-control" value="{{ @$homeSec->shops_stat1_value ?? '250+' }}" placeholder="250+">
                    </div>
                    <div class="form-group p-0 mb-3">
                      <label class="font-weight-bold">{{ __('Label / Title') }}</label>
                      <input type="text" name="shops_stat1_label" class="form-control" value="{{ @$homeSec->shops_stat1_label ?? 'Stores Launched' }}" placeholder="Stores Launched">
                    </div>
                    <div class="form-group p-0 mb-0">
                      <label class="font-weight-bold">{{ __('FontAwesome Icon Class') }}</label>
                      <input type="text" name="shops_stat1_icon" class="form-control" value="{{ @$homeSec->shops_stat1_icon ?? 'fas fa-store' }}" placeholder="fas fa-store">
                    </div>
                  </div>
                </div>
              </div>

              {{-- Stat 2 --}}
              <div class="col-lg-6 col-md-6 mb-4">
                <div class="card border h-100" style="border-radius: 12px; background: #fafbfe;">
                  <div class="card-header bg-light py-2 d-flex align-items-center justify-content-between">
                    <span class="font-weight-bold text-dark"><i class="fas fa-gem text-info mr-1"></i> {{ __('Stat 2 (Premium Themes)') }}</span>
                    <span class="badge badge-info text-white">{{ __('Position 2') }}</span>
                  </div>
                  <div class="card-body p-3">
                    <div class="form-group p-0 mb-3">
                      <label class="font-weight-bold">{{ __('Amount / Count Value') }}</label>
                      <input type="text" name="shops_stat2_value" class="form-control" value="{{ @$homeSec->shops_stat2_value ?? '15+' }}" placeholder="15+">
                    </div>
                    <div class="form-group p-0 mb-3">
                      <label class="font-weight-bold">{{ __('Label / Title') }}</label>
                      <input type="text" name="shops_stat2_label" class="form-control" value="{{ @$homeSec->shops_stat2_label ?? 'Premium Themes' }}" placeholder="Premium Themes">
                    </div>
                    <div class="form-group p-0 mb-0">
                      <label class="font-weight-bold">{{ __('FontAwesome Icon Class') }}</label>
                      <input type="text" name="shops_stat2_icon" class="form-control" value="{{ @$homeSec->shops_stat2_icon ?? 'fas fa-gem' }}" placeholder="fas fa-gem">
                    </div>
                  </div>
                </div>
              </div>

              {{-- Stat 3 --}}
              <div class="col-lg-6 col-md-6 mb-4">
                <div class="card border h-100" style="border-radius: 12px; background: #fafbfe;">
                  <div class="card-header bg-light py-2 d-flex align-items-center justify-content-between">
                    <span class="font-weight-bold text-dark"><i class="fas fa-shield-alt text-success mr-1"></i> {{ __('Stat 3 (Uptime Guarantee)') }}</span>
                    <span class="badge badge-success text-white">{{ __('Position 3') }}</span>
                  </div>
                  <div class="card-body p-3">
                    <div class="form-group p-0 mb-3">
                      <label class="font-weight-bold">{{ __('Amount / Count Value') }}</label>
                      <input type="text" name="shops_stat3_value" class="form-control" value="{{ @$homeSec->shops_stat3_value ?? '99.99%' }}" placeholder="99.99%">
                    </div>
                    <div class="form-group p-0 mb-3">
                      <label class="font-weight-bold">{{ __('Label / Title') }}</label>
                      <input type="text" name="shops_stat3_label" class="form-control" value="{{ @$homeSec->shops_stat3_label ?? 'Uptime Guarantee' }}" placeholder="Uptime Guarantee">
                    </div>
                    <div class="form-group p-0 mb-0">
                      <label class="font-weight-bold">{{ __('FontAwesome Icon Class') }}</label>
                      <input type="text" name="shops_stat3_icon" class="form-control" value="{{ @$homeSec->shops_stat3_icon ?? 'fas fa-shield-alt' }}" placeholder="fas fa-shield-alt">
                    </div>
                  </div>
                </div>
              </div>

              {{-- Stat 4 --}}
              <div class="col-lg-6 col-md-6 mb-4">
                <div class="card border h-100" style="border-radius: 12px; background: #fafbfe;">
                  <div class="card-header bg-light py-2 d-flex align-items-center justify-content-between">
                    <span class="font-weight-bold text-dark"><i class="fas fa-user-friends text-danger mr-1"></i> {{ __('Stat 4 (Happy Merchants)') }}</span>
                    <span class="badge badge-danger text-white">{{ __('Position 4') }}</span>
                  </div>
                  <div class="card-body p-3">
                    <div class="form-group p-0 mb-3">
                      <label class="font-weight-bold">{{ __('Amount / Count Value') }}</label>
                      <input type="text" name="shops_stat4_value" class="form-control" value="{{ @$homeSec->shops_stat4_value ?? '500+' }}" placeholder="500+">
                    </div>
                    <div class="form-group p-0 mb-3">
                      <label class="font-weight-bold">{{ __('Label / Title') }}</label>
                      <input type="text" name="shops_stat4_label" class="form-control" value="{{ @$homeSec->shops_stat4_label ?? 'Happy Merchants' }}" placeholder="Happy Merchants">
                    </div>
                    <div class="form-group p-0 mb-0">
                      <label class="font-weight-bold">{{ __('FontAwesome Icon Class') }}</label>
                      <input type="text" name="shops_stat4_icon" class="form-control" value="{{ @$homeSec->shops_stat4_icon ?? 'fas fa-user-friends' }}" placeholder="fas fa-user-friends">
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="text-center mt-3">
              <button type="submit" class="btn btn-success font-weight-bold px-4 py-2">
                <i class="fas fa-save mr-1"></i> {{ __('Save Stores Launched Counters') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  {{-- create modal --}}
  @includeIf('admin.about.counter-section.create')
  {{-- edit modal --}}
  @includeIf('admin.about.counter-section.edit')
@endsection
@section('scripts')
  <script src="{{ asset('assets/user/js/image-text.js') }}"></script>
@endsection

@extends('user.layout')

@php
  $selLang = \App\Models\User\Language::where('code', request()->input('language'))->first();
  $userLanguages = \App\Models\User\Language::where('user_id', Auth::guard('web')->user()->id)->get();
@endphp
@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Items') }}</h4>
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
        <a href="#">{{ __('Shop Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Products') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Items') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card shadow-sm border-0" style="border-radius: 12px;">
        <div class="card-header border-0 pb-0 pt-4 px-4 bg-transparent d-flex justify-content-between align-items-center flex-wrap">
          <div class="d-flex align-items-center mb-3 mb-xl-0">
            <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 44px; height: 44px; border-radius: 12px; background: rgba(13, 110, 253, 0.1); flex-shrink: 0;">
              <i class="fas fa-store text-primary" style="font-size: 20px;"></i>
            </span>
            <div>
              <h4 class="card-title font-weight-bold mb-1" style="font-size: 18px;">{{ __('Items') }}</h4>
              <p class="text-muted mb-0" style="font-size: 13px;">{{ __('Manage and organize your store items.') }}</p>
            </div>
          </div>
          <div class="d-flex align-items-center gap-2 flex-wrap">
            @if (!empty($userLanguages))
              <select name="language" class="form-control form-control-sm mr-2 mb-2 mb-md-0" style="border-radius: 8px; width: auto; height: 38px;"
                onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                <option value="" selected disabled>{{ __('Select Language') }}</option>
                @foreach ($userLanguages as $language)
                  <option value="{{ $language->code }}" {{ $language->code == request()->input('language') ? 'selected' : '' }}>
                    {{ $language->name }}
                  </option>
                @endforeach
              </select>
            @endif

            <form action="" method="get" class="mr-2 mb-2 mb-md-0">
              <input type="hidden" name="language" value="{{ request()->input('language') }}">
              <div class="input-group" style="width: 260px;">
                <input type="text" name="title" class="form-control form-control-sm" style="border-radius: 8px 0 0 8px; height: 38px; border: 1px solid rgba(0,0,0,0.15);" placeholder="{{ __('Search by title, SKU...') }}"
                  value="{{ request()->input('title') }}">
                <div class="input-group-append">
                  <button class="btn btn-primary btn-sm d-inline-flex align-items-center justify-content-center" style="border-radius: 0 8px 8px 0; height: 38px; padding: 0 14px; background: #0d6efd; border-color: #0d6efd;" type="submit">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </form>

            <button type="button" class="btn btn-warning text-white btn-sm font-weight-bold d-inline-flex align-items-center mr-1 mb-2 mb-md-0" style="border-radius: 8px; padding: 8px 14px; height: 38px; background: #ff9f43; border-color: #ff9f43;" data-toggle="modal" data-target="#bulkImagesModal" onclick="loadBulkImagesGallery()">
              <i class="fas fa-images mr-1"></i> {{ __('Upload Images') }}
            </button>
            <a href="{{ route('user.item.sample_csv') }}" class="btn btn-outline-secondary btn-sm font-weight-bold d-inline-flex align-items-center mr-1 mb-2 mb-md-0" style="border-radius: 8px; padding: 8px 14px; height: 38px;" title="{{ __('Download Sample CSV') }}">
              <i class="fas fa-file-csv mr-1"></i> {{ __('Sample CSV') }}
            </a>
            <button type="button" class="btn btn-info btn-sm font-weight-bold d-inline-flex align-items-center mr-1 mb-2 mb-md-0" style="border-radius: 8px; padding: 8px 14px; height: 38px;" data-toggle="modal" data-target="#importCsvModal">
              <i class="fas fa-file-import mr-1"></i> {{ __('Import CSV') }}
            </button>
            <a href="{{ route('user.item.export_csv') . '?language=' . request()->input('language') }}" class="btn btn-success btn-sm font-weight-bold d-inline-flex align-items-center mr-1 mb-2 mb-md-0" style="border-radius: 8px; padding: 8px 14px; height: 38px;">
              <i class="fas fa-file-export mr-1"></i> {{ __('Export CSV') }}
            </a>
            <a href="{{ route('user.item.type') }}" class="btn btn-primary btn-sm font-weight-bold d-inline-flex align-items-center mb-2 mb-md-0" style="border-radius: 8px; padding: 8px 16px; height: 38px; background: #0d6efd; border-color: #0d6efd;">
              <i class="fas fa-plus mr-2"></i> {{ __('Add Item') }}
            </a>
            <button class="btn btn-danger btn-sm font-weight-bold mb-2 mb-md-0 d-none bulk-delete" style="border-radius: 8px; height: 38px;" data-href="{{ route('user.item.bulk.delete') }}">
              <i class="flaticon-interface-5 mr-1"></i> {{ __('Delete') }}
            </button>
          </div>
        </div>

        @php
          $catBadges = [
            ['bg' => 'rgba(13, 110, 253, 0.08)', 'color' => '#0d6efd', 'border' => 'rgba(13, 110, 253, 0.2)'],
            ['bg' => 'rgba(40, 167, 69, 0.08)', 'color' => '#28a745', 'border' => 'rgba(40, 167, 69, 0.2)'],
            ['bg' => 'rgba(253, 126, 20, 0.08)', 'color' => '#fd7e14', 'border' => 'rgba(253, 126, 20, 0.2)'],
            ['bg' => 'rgba(111, 66, 193, 0.08)', 'color' => '#6f42c1', 'border' => 'rgba(111, 66, 193, 0.2)'],
            ['bg' => 'rgba(232, 62, 140, 0.08)', 'color' => '#e83e8c', 'border' => 'rgba(232, 62, 140, 0.2)'],
            ['bg' => 'rgba(23, 162, 184, 0.08)', 'color' => '#17a2b8', 'border' => 'rgba(23, 162, 184, 0.2)'],
            ['bg' => 'rgba(108, 117, 125, 0.08)', 'color' => '#6c757d', 'border' => 'rgba(108, 117, 125, 0.2)'],
          ];
        @endphp

        <div class="card-body px-4 pt-3 pb-4">
          <div class="row">
            <div class="col-lg-12">
              @if (count($items) == 0)
                <h3 class="text-center py-5">{{ __('NO ITEMS FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-hover mt-3" style="border-collapse: separate; border-spacing: 0 8px;">
                    <thead>
                      <tr style="background: rgba(0,0,0,0.02);">
                        <th scope="col" class="border-top-0 border-bottom-0" style="border-radius: 8px 0 0 8px; width: 40px;">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col" class="border-top-0 border-bottom-0 font-weight-bold" style="width: 80px;">{{ __('Image') }}</th>
                        <th scope="col" class="border-top-0 border-bottom-0 font-weight-bold">{{ __('Title') }}</th>
                        <th scope="col" class="border-top-0 border-bottom-0 font-weight-bold">{{ __('Price') }} ({{ $currency->text }})</th>
                        <th scope="col" class="border-top-0 border-bottom-0 font-weight-bold">{{ __('Type') }}</th>
                        <th scope="col" class="border-top-0 border-bottom-0 font-weight-bold">{{ __('SKU') }}</th>
                        <th scope="col" class="border-top-0 border-bottom-0 font-weight-bold">{{ __('Category') }}</th>
                        <th scope="col" class="border-top-0 border-bottom-0 font-weight-bold">{{ __('Variants') }}</th>
                        <th scope="col" class="border-top-0 border-bottom-0 font-weight-bold">{{ __('Featured') }}</th>
                        <th scope="col" class="border-top-0 border-bottom-0 font-weight-bold text-center" style="border-radius: 0 8px 8px 0;">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($items as $key => $item)
                        @php
                          $badgeStyle = $catBadges[$key % count($catBadges)];
                        @endphp
                        <tr style="background: #ffffff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); border-radius: 8px;">
                          <td class="align-middle" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04); border-left: 1px solid rgba(0,0,0,0.04); border-radius: 8px 0 0 8px;">
                            <input type="checkbox" class="bulk-check" data-val="{{ $item->item_id }}">
                          </td>
                          <td class="align-middle" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04);">
                            @php
                              $thumbSrc = asset('assets/admin/img/noimage.jpg');
                              if (!empty($item->thumbnail)) {
                                  if (filter_var($item->thumbnail, FILTER_VALIDATE_URL)) {
                                      $thumbSrc = $item->thumbnail;
                                  } else {
                                      $cleanImgName = basename(parse_url($item->thumbnail, PHP_URL_PATH));
                                      if (!empty($cleanImgName) && file_exists(public_path('assets/front/img/user/items/thumbnail/' . $cleanImgName))) {
                                          $thumbSrc = asset('assets/front/img/user/items/thumbnail/' . $cleanImgName);
                                      }
                                  }
                              }
                            @endphp
                            <img src="{{ $thumbSrc }}" onerror="this.onerror=null;this.src='{{ asset('assets/admin/img/noimage.jpg') }}';" alt="Product Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(0,0,0,0.08);">
                          </td>
                          <td class="align-middle font-weight-bold" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04);">
                            <a href="{{ route('front.user.productDetails', [Auth::user('web')->username, 'slug' => $item->slug]) }}"
                              target="_blank" class="text-decoration-none" style="color: #212529; font-size: 14px;">
                              {{ truncateString($item->title, 50) }}
                            </a>
                          </td>
                          <td class="align-middle font-weight-bold" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04); font-size: 14px;">
                            {{ symbolPrice($currency->symbol_position, $currency->symbol, $item->current_price) }}
                          </td>
                          <td class="align-middle text-capitalize text-muted" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04); font-size: 14px;">
                            {{ $item->type }}
                          </td>
                          <td class="align-middle text-muted font-weight-500" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04); font-size: 14px;">
                            {{ $item->sku }}
                          </td>
                          <td class="align-middle" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04);">
                            @if ($item->category)
                              <span class="badge font-weight-bold" style="background: {{ $badgeStyle['bg'] }}; color: {{ $badgeStyle['color'] }}; border: 1px solid {{ $badgeStyle['border'] }}; border-radius: 20px; padding: 6px 14px; font-size: 12px;">
                                {{ convertUtf8($item->category) }}
                              </span>
                            @else
                              <span class="text-muted">-</span>
                            @endif
                          </td>
                          <td class="align-middle" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04);">
                            @if ($item->type != 'digital')
                              <a class="btn btn-outline-secondary btn-sm font-weight-bold" style="border-radius: 20px; padding: 4px 12px; font-size: 12px;"
                                href="{{ route('user.item.variations', $item->item_id) . '?language=' . request()->input('language') }}">
                                {{ __('Manage') }}
                              </a>
                            @else
                              <span class="text-muted font-weight-bold pl-3">-</span>
                            @endif
                          </td>
                          <td class="align-middle" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04);">
                            <form class="d-inline-block" action="{{ route('user.item.feature') }}"
                              id="featureForm{{ $item->item_id }}" method="POST">
                              @csrf
                              <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                              <select name="is_feature" class="form-control form-control-sm font-weight-bold {{ $item->is_feature == 1 ? 'text-success' : 'text-danger' }}" style="border-radius: 20px; width: auto; height: 28px; padding: 0 24px 0 12px; font-size: 12px; border: 1px solid {{ $item->is_feature == 1 ? '#28a745' : '#dc3545' }}; background-color: {{ $item->is_feature == 1 ? 'rgba(40, 167, 69, 0.08)' : 'rgba(220, 53, 69, 0.08)' }}; cursor: pointer;"
                                onchange="document.getElementById('featureForm{{ $item->item_id }}').submit();">
                                <option value="1" class="text-dark" {{ $item->is_feature == 1 ? 'selected' : '' }}>{{ __('Yes') }}</option>
                                <option value="0" class="text-dark" {{ $item->is_feature == 0 ? 'selected' : '' }}>{{ __('No') }}</option>
                              </select>
                            </form>
                          </td>
                          <td class="align-middle text-center" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04); border-right: 1px solid rgba(0,0,0,0.04); border-radius: 0 8px 8px 0;">
                            <div class="dropdown d-inline-block">
                              <button class="btn btn-light btn-sm d-inline-flex align-items-center justify-content-center text-muted" type="button" style="width: 32px; height: 32px; border-radius: 8px; padding: 0; border: 1px solid rgba(0,0,0,0.08); background: transparent;" data-toggle="dropdown">
                                <i class="fas fa-ellipsis-v" style="font-size: 12px;"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-right shadow-sm border-0" style="border-radius: 10px;">
                                <a class="dropdown-item py-2 font-weight-500" href="#"
                                  @if ($total_item > $item_limit) @else data-toggle="modal"
                                  data-target="#flashmodal{{ $item->item_id }}" @endif><i class="fas fa-bolt text-warning mr-2"></i> {{ __('Flash Sale') }}</a>
                                <a class="dropdown-item py-2 font-weight-500" {{ $total_item > $item_limit ? 'disabled' : '' }}
                                  href="{{ route('user.item.edit', $item->item_id) . '?language=' . request()->input('language') }}"><i class="fas fa-pen text-primary mr-2"></i> {{ __('Edit') }}</a>
                                <form class="deleteForm d-block" action="{{ route('user.item.delete') }}" method="post">
                                  @csrf
                                  <input type="hidden" name="item_id" value="{{ $item->item_id }}">
                                  <input type="hidden" name="language_code" value="{{ request()->input('language') }}">
                                  <button type="submit" class="dropdown-item py-2 font-weight-500 text-danger itemdeletebtn deleteBtn">
                                    <i class="fas fa-trash text-danger mr-2"></i> {{ __('Delete') }}
                                  </button>
                                </form>
                              </div>
                            </div>

                            <!-- Flash Sale Modal -->
                            <div class="modal fade text-left" id="flashmodal{{ $item->item_id }}" tabindex="-1" role="dialog"
                              aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content border-0 shadow" style="border-radius: 12px;">
                                  <div class="modal-header border-bottom">
                                    <h5 class="modal-title font-weight-bold" id="exampleModalLongTitle">
                                      {{ __('Flash Sale Setting') }}
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <div class="modal-body p-4">
                                    <form class="modal-form" enctype="multipart/form-data"
                                      action="{{ route('user.item.setFlashSale', $item->item_id) }}" method="POST">
                                      @csrf
                                      <div class="form-group p-0 mb-3">
                                        <label class="font-weight-bold mb-2">{{ __('Status') }}</label>
                                        <div class="selectgroup w-100">
                                          <label class="selectgroup-item">
                                            <input type="radio" name="status" value="1"
                                              class="selectgroup-input" {{ $item->flash == 1 ? 'checked' : '' }}>
                                            <span class="selectgroup-button font-weight-bold" style="border-radius: 8px 0 0 8px;">{{ __('Active') }}</span>
                                          </label>
                                          <label class="selectgroup-item">
                                            <input type="radio" name="status" value="0"
                                              class="selectgroup-input" {{ $item->flash == 0 ? 'checked' : '' }}>
                                            <span class="selectgroup-button font-weight-bold" style="border-radius: 0 8px 8px 0;">{{ __('Deactive') }}</span>
                                          </label>
                                        </div>
                                      </div>

                                      <div class="form-group p-0 mb-3">
                                        <label class="font-weight-bold mb-2">{{ __('Discount') }} (%)</label>
                                        <input type="number" value="{{ $item->flash_amount }}" name="flash_amount"
                                          class="form-control" style="border-radius: 8px; height: 40px;" placeholder="{{ __('Enter flash deal percentage') }}">
                                        <p class="mb-0 text-danger em errflash_amount"></p>
                                      </div>

                                      <div class="form-group p-0 mb-3">
                                        <label class="font-weight-bold mb-2">{{ __('Start Date') }} <span class="text-danger">*</span></label>
                                        <input type="text" value="{{ $item->start_date }}" name="start_date"
                                          class="form-control datepicker" style="border-radius: 8px; height: 40px;" autocomplete="off" placeholder="YYYY-MM-DD">
                                        <p class="mb-0 text-danger em errstart_date"></p>
                                      </div>
                                      <div class="form-group p-0 mb-3">
                                        <label class="font-weight-bold mb-2">{{ __('Start Time') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="start_time" value="{{ $item->start_time }}"
                                          class="form-control flatpickr" style="border-radius: 8px; height: 40px;" autocomplete="off" placeholder="00:00">
                                        <p class="mb-0 text-danger em errstart_time"></p>
                                      </div>
                                      <div class="form-group p-0 mb-3">
                                        <label class="font-weight-bold mb-2">{{ __('End Date') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="end_date" value="{{ $item->end_date }}"
                                          class="form-control datepicker" style="border-radius: 8px; height: 40px;" autocomplete="off" placeholder="YYYY-MM-DD">
                                        <p class="mb-0 text-danger em errend_date"></p>
                                      </div>
                                      <div class="form-group p-0 mb-4">
                                        <label class="font-weight-bold mb-2">{{ __('End Time') }} <span class="text-danger">*</span></label>
                                        <input type="text" name="end_time" value="{{ $item->end_time }}"
                                          class="form-control flatpickr" style="border-radius: 8px; height: 40px;" autocomplete="off" placeholder="00:00">
                                        <p class="mb-0 text-danger em errend_time"></p>
                                      </div>
                                      <div class="modal-footer px-0 pb-0 border-0">
                                        <button type="button" class="btn btn-outline-secondary font-weight-bold" style="border-radius: 8px;" data-dismiss="modal">{{ __('Close') }}</button>
                                        <button type="submit" class="btn btn-primary font-weight-bold" style="border-radius: 8px; background: #0d6efd; border-color: #0d6efd;">{{ __('Submit') }}</button>
                                      </div>
                                    </form>
                                  </div>
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
        <div class="card-footer border-0 pt-0 pb-4 px-4 bg-transparent d-flex justify-content-between align-items-center flex-wrap">
          <div class="text-muted" style="font-size: 13px;">
            @if (count($items) > 0)
              {{ __('Showing') }} {{ $items->firstItem() }} {{ __('to') }} {{ $items->lastItem() }} {{ __('of') }} {{ $items->total() }} {{ __('entries') }}
            @endif
          </div>
          <div>
            {{ $items->appends(['language' => request()->input('language'), 'title' => request()->input('title')])->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Import CSV Modal -->
  <div class="modal fade text-left" id="importCsvModal" tabindex="-1" role="dialog" aria-labelledby="importCsvModalTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content border-0 shadow" style="border-radius: 12px;">
        <div class="modal-header border-bottom">
          <h5 class="modal-title font-weight-bold" id="importCsvModalTitle">
            <i class="fas fa-file-import text-info mr-2"></i>{{ __('Import Products via CSV') }}
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="importModalCloseBtn">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('user.item.import_csv') }}" method="POST" enctype="multipart/form-data" id="csvImportForm">
          @csrf
          <div class="modal-body p-4">
            <div id="importFormFields">
              <div class="alert alert-info py-2 px-3 mb-3" style="border-radius: 8px; font-size: 13px;">
                <i class="fas fa-info-circle mr-1"></i> {{ __('Upload CSV file to import products in bulk. Product import respects your package limit.') }}
              </div>
              <div class="form-group p-0 mb-3">
                <label class="font-weight-bold mb-2">{{ __('Select CSV File') }} <span class="text-danger">*</span></label>
                <input type="file" name="csv_file" class="form-control-file p-2 border rounded" accept=".csv, .txt" required style="border-radius: 8px;">
                <small class="form-text text-muted mt-2">
                  {{ __('Allowed file type: .csv (Max: 5MB)') }}
                </small>
              </div>
              <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
                <span class="text-muted" style="font-size: 12px;">{{ __('Need standard CSV format?') }}</span>
                <a href="{{ route('user.item.sample_csv') }}" class="btn btn-link btn-sm p-0 font-weight-bold">
                  <i class="fas fa-download mr-1"></i>{{ __('Download Sample CSV') }}
                </a>
              </div>
            </div>
            <!-- Loading Animation -->
            <div id="importLoadingState" class="d-none text-center py-4">
              <div class="spinner-border text-info mb-3" style="width: 3.5rem; height: 3.5rem;" role="status">
                <span class="sr-only">{{ __('Loading...') }}</span>
              </div>
              <h5 class="font-weight-bold text-dark mb-2">{{ __('Importing Products...') }}</h5>
              <p class="text-muted mb-0" style="font-size: 13px;">{{ __('Please wait while your CSV file is being processed and products are imported.') }}</p>
            </div>
          </div>
          <div class="modal-footer px-4 pb-4 border-0" id="importModalFooter">
            <button type="button" class="btn btn-outline-secondary font-weight-bold" style="border-radius: 8px;" data-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" id="importSubmitBtn" class="btn btn-info font-weight-bold" style="border-radius: 8px;">
              <i class="fas fa-upload mr-1"></i>{{ __('Upload & Import') }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Bulk Images Upload Modal -->
  <div class="modal fade text-left" id="bulkImagesModal" tabindex="-1" role="dialog" aria-labelledby="bulkImagesModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content border-0 shadow" style="border-radius: 12px;">
        <div class="modal-header border-bottom" style="background: #f8f9fa; border-radius: 12px 12px 0 0;">
          <h5 class="modal-title font-weight-bold" id="bulkImagesModalTitle">
            <i class="fas fa-images text-warning mr-2"></i>{{ __('Bulk Product Images Uploader') }}
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4">
          <div class="alert alert-warning py-2 px-3 mb-3" style="border-radius: 8px; font-size: 13px; background: #fff8ec; border-color: #ffe6b3; color: #856404;">
            <i class="fas fa-lightbulb mr-1"></i> {{ __('Upload product images from your computer here first. Copy the filename or URL to use in your CSV file before importing.') }}
          </div>

          <!-- Drag & Drop / File Input Box -->
          <form id="bulkImagesForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group border rounded p-4 text-center bg-light" style="border: 2px dashed #ff9f43 !important; border-radius: 10px; cursor: pointer;" onclick="document.getElementById('bulkImageInput').click();">
              <i class="fas fa-cloud-upload-alt text-warning mb-2" style="font-size: 42px;"></i>
              <h6 class="font-weight-bold text-dark mb-1">{{ __('Click or Drag & Drop Images Here') }}</h6>
              <p class="text-muted mb-2" style="font-size: 12px;">{{ __('Select multiple image files (JPG, PNG, WEBP, SVG max 10MB per file)') }}</p>
              <input type="file" id="bulkImageInput" name="images[]" multiple accept="image/*" class="d-none" onchange="handleBulkFilesSelected(this)">
              <div id="selectedFilesBadge" class="mt-2 text-info font-weight-bold" style="font-size: 13px;"></div>
            </div>
            <div class="text-right mb-4">
              <button type="submit" id="uploadImagesSubmitBtn" class="btn btn-warning text-white font-weight-bold" style="border-radius: 8px; background: #ff9f43; border-color: #ff9f43;" disabled>
                <i class="fas fa-upload mr-1"></i>{{ __('Upload All Selected Images') }}
              </button>
            </div>
          </form>

          <!-- Uploaded Gallery Header -->
          <div class="d-flex justify-content-between align-items-center mb-3 pt-3 border-top">
            <h6 class="font-weight-bold mb-0 text-dark">
              <i class="fas fa-photo-video text-secondary mr-1"></i> {{ __('Uploaded Images Gallery') }}
            </h6>
            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="loadBulkImagesGallery()" style="border-radius: 6px; font-size: 12px;">
              <i class="fas fa-sync-alt mr-1"></i>{{ __('Refresh') }}
            </button>
          </div>

          <!-- Gallery List Container -->
          <div id="bulkImagesGallery" class="row" style="max-height: 320px; overflow-y: auto;">
            <div class="col-12 text-center py-4 text-muted">
              <i class="fas fa-spinner fa-spin mr-1"></i>{{ __('Loading gallery...') }}
            </div>
          </div>
        </div>
        <div class="modal-footer px-4 pb-4 border-0">
          <button type="button" class="btn btn-secondary font-weight-bold" style="border-radius: 8px;" data-dismiss="modal">{{ __('Close') }}</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var csvForm = document.getElementById('csvImportForm');
      if (csvForm) {
        csvForm.addEventListener('submit', function() {
          document.getElementById('importFormFields').classList.add('d-none');
          document.getElementById('importLoadingState').classList.remove('d-none');
          document.getElementById('importModalFooter').classList.add('d-none');
          var closeBtn = document.getElementById('importModalCloseBtn');
          if (closeBtn) closeBtn.style.display = 'none';
        });
      }
    });

    function handleBulkFilesSelected(input) {
      var files = input.files;
      var badge = document.getElementById('selectedFilesBadge');
      var btn = document.getElementById('uploadImagesSubmitBtn');
      if (files && files.length > 0) {
        badge.innerHTML = '<i class="fas fa-check-circle text-success mr-1"></i> ' + files.length + ' {{ __("files selected") }}';
        btn.removeAttribute('disabled');
      } else {
        badge.innerHTML = '';
        btn.setAttribute('disabled', 'disabled');
      }
    }

    function loadBulkImagesGallery() {
      var gallery = document.getElementById('bulkImagesGallery');
      if (!gallery) return;
      gallery.innerHTML = '<div class="col-12 text-center py-4 text-muted"><i class="fas fa-spinner fa-spin mr-1"></i> {{ __("Loading gallery...") }}</div>';

      fetch('{{ route("user.item.get_bulk_images") }}')
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success' && data.images.length > 0) {
            var html = '';
            data.images.forEach(function(img) {
              html += '<div class="col-6 col-sm-4 col-md-3 mb-3" id="imgcard-' + encodeURIComponent(img.filename) + '">';
              html += '  <div class="card h-100 border shadow-sm p-2 text-center" style="border-radius: 10px;">';
              html += '    <img src="' + img.url + '" onerror="this.onerror=null;this.src=\'{{ asset("assets/admin/img/noimage.jpg") }}\';" style="height: 80px; object-fit: cover; border-radius: 6px;" class="w-100 mb-2">';
              html += '    <small class="text-truncate d-block font-weight-bold text-dark mb-1" style="font-size: 11px;" title="' + img.filename + '">' + img.filename + '</small>';
              html += '    <div class="d-flex justify-content-between mt-1">';
              html += '      <button type="button" class="btn btn-outline-info btn-xs font-weight-bold flex-grow-1 mr-1" onclick="copyImageFilename(\'' + img.filename + '\', this)" style="font-size: 10px; border-radius: 4px; padding: 2px 4px;">';
              html += '        <i class="fas fa-copy mr-1"></i>{{ __("Copy") }}';
              html += '      </button>';
              html += '      <button type="button" class="btn btn-outline-danger btn-xs font-weight-bold" onclick="deleteBulkImage(\'' + img.filename + '\', this)" style="font-size: 10px; border-radius: 4px; padding: 2px 6px;" title="{{ __("Delete Image") }}">';
              html += '        <i class="fas fa-trash-alt"></i>';
              html += '      </button>';
              html += '    </div>';
              html += '  </div>';
              html += '</div>';
            });
            gallery.innerHTML = html;
          } else {
            gallery.innerHTML = '<div class="col-12 text-center py-4 text-muted" style="font-size: 13px;">{{ __("No uploaded images found yet.") }}</div>';
          }
        })
        .catch(err => {
          gallery.innerHTML = '<div class="col-12 text-center py-4 text-danger">{{ __("Failed to load images.") }}</div>';
        });
    }

    function copyImageFilename(filename, btn) {
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(filename).then(function() {
          var oldText = btn.innerHTML;
          btn.innerHTML = '<i class="fas fa-check text-success"></i> {{ __("Copied!") }}';
          setTimeout(function() { btn.innerHTML = oldText; }, 2000);
        });
      } else {
        var tempInput = document.createElement('input');
        tempInput.value = filename;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand('copy');
        document.body.removeChild(tempInput);
        var oldText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check text-success"></i> {{ __("Copied!") }}';
        setTimeout(function() { btn.innerHTML = oldText; }, 2000);
      }
    }

    document.getElementById('bulkImagesForm')?.addEventListener('submit', function(e) {
      e.preventDefault();
      var formData = new FormData(this);
      var btn = document.getElementById('uploadImagesSubmitBtn');
      btn.setAttribute('disabled', 'disabled');
      btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> {{ __("Uploading...") }}';

      fetch('{{ route("user.item.upload_bulk_images") }}', {
        method: 'POST',
        body: formData,
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
      })
      .then(response => response.json())
      .then(data => {
        btn.innerHTML = '<i class="fas fa-upload mr-1"></i> {{ __("Upload All Selected Images") }}';
        if (data.status === 'success') {
          document.getElementById('bulkImageInput').value = '';
          document.getElementById('selectedFilesBadge').innerHTML = '<span class="text-success">' + data.message + '</span>';
          loadBulkImagesGallery();
        } else {
          alert(data.message || '{{ __("Upload failed.") }}');
        }
      })
      .catch(err => {
        btn.innerHTML = '<i class="fas fa-upload mr-1"></i> {{ __("Upload All Selected Images") }}';
        alert('{{ __("Upload failed. Check image file sizes.") }}');
      });
    });

    function deleteBulkImage(filename, btn) {
      if (!confirm('{{ __("Are you sure you want to delete this image file?") }}')) return;
      btn.setAttribute('disabled', 'disabled');
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

      fetch('{{ route("user.item.delete_bulk_image") }}', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ filename: filename })
      })
      .then(response => response.json())
      .then(data => {
        if (data.status === 'success') {
          loadBulkImagesGallery();
        } else {
          alert(data.message || '{{ __("Failed to delete image.") }}');
        }
      })
      .catch(err => {
        alert('{{ __("Error deleting image.") }}');
      });
    }
  </script>
@endsection

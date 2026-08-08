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
                            <img src="{{ $item->thumbnail ? asset('assets/front/img/user/items/thumbnail/' . $item->thumbnail) : asset('assets/admin/img/noimage.jpg') }}" alt="Product Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(0,0,0,0.08);">
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
@endsection

@extends('user.layout')

@php
  $selLang = \App\Models\User\Language::where([
      ['user_id', Auth::guard('web')->user()->id],
      ['code', request()->input('language')],
  ])->first();
  $userLanguages = \App\Models\User\Language::where('user_id', Auth::guard('web')->user()->id)->get();
@endphp
@includeIf('user.partials.rtl-style')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Variations') }}</h4>
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
        <a href="#">{{ __('Variants') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Variations') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card dark-card" style="border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: none;">
        <div class="card-header dark-border" style="padding: 20px 25px; border-bottom: 1px solid rgba(0,0,0,0.05);">
          <div class="row align-items-center">
            <div class="col-lg-6 col-md-6 d-flex align-items-center mb-3 mb-md-0">
              <div class="stat-card-icon-box" style="width: 48px; height: 48px; border-radius: 12px; background: rgba(13, 110, 253, 0.1); color: #0d6efd; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;">
                <i class="fas fa-sliders-h"></i>
              </div>
              <div class="ml-3">
                <h4 class="card-title mb-1 dark-label" style="font-weight: 700; font-size: 18px;">{{ __('Variations') }}</h4>
                <p class="text-muted mb-0 dark-text" style="font-size: 13px;">{{ __('Manage product variations easily and efficiently.') }}</p>
              </div>
            </div>
            <div class="col-lg-6 col-md-6 d-flex align-items-center justify-content-md-end justify-content-start flex-wrap">
              @if (!empty($userLanguages))
                <select name="language" class="form-control dark-input mr-2 mb-2 mb-sm-0" style="width: auto; min-width: 140px; border-radius: 8px; font-weight: 500;"
                  onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                  <option value="" selected disabled>{{ __('Select a Language') }}
                  </option>
                  @foreach ($userLanguages as $language)
                    <option value="{{ $language->code }}"
                      {{ $language->code == request()->input('language') ? 'selected' : '' }}>
                      {{ $language->name }}</option>
                  @endforeach
                </select>
              @endif
              <a href="{{ route('user.variant.create', ['language' => $selLang->code]) }}"
                class="btn btn-primary btn-sm mb-2 mb-sm-0" style="border-radius: 8px; padding: 8px 18px; font-weight: 600;"><i class="fas fa-plus mr-1"></i>
                {{ __('Add Variation') }}</a>
              <button class="btn btn-danger btn-sm ml-2 d-none bulk-delete mb-2 mb-sm-0" style="border-radius: 8px; padding: 8px 18px; font-weight: 600;"
                data-href="{{ route('user.variant.bulk_delete') }}"><i class="flaticon-interface-5 mr-1"></i>
                {{ __('Delete') }}</button>
            </div>
          </div>
        </div>
        <div class="card-body" style="padding: 25px;">
          <div class="row">
            <div class="col-lg-12">
              @if (count($variants) == 0)
                <h3 class="text-center dark-label my-5">{{ __('NO VARIATION FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table mt-2 dark-table" id="basic-datatables" style="width: 100%; border-collapse: separate; border-spacing: 0 8px;">
                    <thead>
                      <tr style="background: rgba(0,0,0,0.02);">
                        <th scope="col" style="border-top: none; padding: 12px 15px; font-weight: 600; width: 40px;">
                          <input type="checkbox" class="bulk-check" data-val="all">
                        </th>
                        <th scope="col" style="border-top: none; padding: 12px 15px; font-weight: 600;">{{ __('Name') }}</th>
                        <th scope="col" style="border-top: none; padding: 12px 15px; font-weight: 600;">{{ __('Category') }}</th>
                        <th scope="col" style="border-top: none; padding: 12px 15px; font-weight: 600;">{{ __('Subcategory') }}</th>
                        <th scope="col" style="border-top: none; padding: 12px 15px; font-weight: 600;">{{ __('Options') }}</th>
                        <th scope="col" style="border-top: none; padding: 12px 15px; font-weight: 600; text-align: center;">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @php
                        $pastelBoxes = [
                          ['bg' => 'rgba(111, 66, 193, 0.12)', 'color' => '#6f42c1', 'icon' => 'fa-tags'],
                          ['bg' => 'rgba(25, 135, 84, 0.12)', 'color' => '#198754', 'icon' => 'fa-cube'],
                          ['bg' => 'rgba(220, 53, 69, 0.12)', 'color' => '#dc3545', 'icon' => 'fa-layer-group'],
                          ['bg' => 'rgba(253, 126, 20, 0.12)', 'color' => '#fd7e14', 'icon' => 'fa-shapes'],
                          ['bg' => 'rgba(13, 202, 240, 0.12)', 'color' => '#0dcaf0', 'icon' => 'fa-adjust'],
                          ['bg' => 'rgba(13, 110, 253, 0.12)', 'color' => '#0d6efd', 'icon' => 'fa-sliders-h'],
                        ];
                      @endphp
                      @foreach ($variants as $key => $item)
                        @php
                          $options = DB::table('variant_option_contents')
                              ->where([['language_id', $selLang->id], ['variant_id', $item->variant_id]])
                              ->get();
                          $style = $pastelBoxes[$key % count($pastelBoxes)];
                        @endphp
                        <tr class="dark-border" style="transition: all 0.2s ease;">
                          <td style="padding: 15px; vertical-align: middle; border-top: 1px solid rgba(0,0,0,0.05);">
                            <input type="checkbox" class="bulk-check" data-val="{{ $item->variant_id }}">
                          </td>
                          <td style="padding: 15px; vertical-align: middle; font-weight: 600; border-top: 1px solid rgba(0,0,0,0.05);" class="dark-label">
                            <div class="d-flex align-items-center">
                              <div style="width: 34px; height: 34px; border-radius: 50%; background: {{ $style['bg'] }}; color: {{ $style['color'] }}; display: inline-flex; align-items: center; justify-content: center; margin-right: 12px; flex-shrink: 0; font-size: 14px;">
                                <i class="fas {{ $style['icon'] }}"></i>
                              </div>
                              <span>{{ $item->name }}</span>
                            </div>
                          </td>
                          <td style="padding: 15px; vertical-align: middle; border-top: 1px solid rgba(0,0,0,0.05);" class="dark-text">{{ @$item->category->name }}</td>
                          <td style="padding: 15px; vertical-align: middle; border-top: 1px solid rgba(0,0,0,0.05);" class="dark-text">{{ @$item->sub_category->name ?? '-' }}</td>
                          <td style="padding: 15px; vertical-align: middle; border-top: 1px solid rgba(0,0,0,0.05);">
                            @if (count($options) > 0)
                              <button type="button" class="btn btn-outline-primary btn-sm" style="border-radius: 20px; padding: 4px 14px; font-size: 12px; font-weight: 600;" data-toggle="modal"
                                data-target="#variation-modal_{{ $item->id }}">
                                <i class="fas fa-eye mr-1"></i> {{ __('Show') }}
                              </button>
                            @else
                              <span class="text-muted">-</span>
                            @endif
                          </td>
                          <td style="padding: 15px; vertical-align: middle; text-align: center; border-top: 1px solid rgba(0,0,0,0.05);">
                            <div class="dropdown">
                              <button class="btn btn-icon btn-sm btn-light border dropdown-toggle dark-input" style="border-radius: 8px; width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; padding: 0;" type="button" id="dropdownMenuButton_{{ $item->id }}"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v text-muted"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-right dark-card" aria-labelledby="dropdownMenuButton_{{ $item->id }}" style="border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                                <a class="dropdown-item dark-label" style="padding: 8px 15px;"
                                  href="{{ route('user.variant.edit', $item->variant_id) . '?language=' . $selLang->code }}"><i class="fas fa-edit mr-2 text-primary"></i> {{ __('Edit') }}</a>
                                <form class="deleteform d-block"
                                  action="{{ route('user.variant.delete', $item->variant_id) }}" method="post">
                                  @csrf
                                  <button type="submit" class="dropdown-item deletebtn dark-label" style="padding: 8px 15px; width: 100%; text-align: left; background: transparent; border: none;">
                                    <i class="fas fa-trash-alt mr-2 text-danger"></i> {{ __('Delete') }}
                                  </button>
                                </form>
                              </div>
                            </div>
                          </td>
                        </tr>

                        @if (count($options) > 0)
                          <div class="modal fade" id="variation-modal_{{ $item->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel_{{ $item->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                              <div class="modal-content dark-card" style="border-radius: 12px; border: none;">
                                <div class="modal-header dark-border" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                                  <h5 class="modal-title dark-label" id="exampleModalLabel_{{ $item->id }}" style="font-weight: 700;">
                                    {{ __('Options for') }} {{ $item->name }}</h5>
                                  <button type="button" class="close dark-label" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body dark-text" style="padding: 20px;">
                                  @foreach ($options as $key => $option)
                                    <div class="d-flex align-items-center mb-2" style="padding: 8px 12px; background: rgba(0,0,0,0.02); border-radius: 6px;">
                                      <span class="badge badge-primary mr-2" style="border-radius: 50%; width: 22px; height: 22px; display: inline-flex; align-items: center; justify-content: center;">{{ $key + 1 }}</span>
                                      <span style="font-weight: 500;">{{ $option->option_name }}</span>
                                    </div>
                                  @endforeach
                                </div>
                              </div>
                            </div>
                          </div>
                        @endif
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
@endsection

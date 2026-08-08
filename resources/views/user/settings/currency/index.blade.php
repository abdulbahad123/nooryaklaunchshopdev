@extends('user.layout')

@php
  $userDefaultLang = \App\Models\User\Language::where([
      ['user_id', \Illuminate\Support\Facades\Auth::id()],
      ['is_default', 1],
  ])->first();
  $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
@endphp


@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Currencies') }}</h4>
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
        <a href="#">{{ __('Currencies') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">

      <div class="card card-premium">
        <div class="card-header d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <div class="card-icon-wrap bg-primary-soft">
              <i class="fas fa-money-check-alt text-primary"></i>
            </div>
            <div class="card-title mb-0" style="font-size: 16px; font-weight: 600; color: #1e293b;">{{ __('Currencies') }}</div>
          </div>

          <div>
            <a href="#" class="btn btn-premium-primary btn-sm" data-toggle="modal"
              data-target="#createModal"><i class="fas fa-plus"></i>
              {{ __('Add Currency') }}</a>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">

              @if (count($currencies) == 0)
                <h3 class="text-center">{{ __('NO Currency FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-premium mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Currency Name') }}</th>
                        <th scope="col">{{ __('Symbol') }}</th>
                        <th scope="col">{{ __('Exchange Rate') }}</th>
                        <th scope="col">{{ __('Default') }}</th>
                        <th scope="col" class="text-center">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($currencies as $key => $currency)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>
                            <div class="d-flex align-items-center">
                              @if(strpos(strtolower($currency->text), 'inr') !== false || strpos(strtolower($currency->text), 'rupee') !== false || $currency->symbol == '₹')
                                <img src="https://flagcdn.com/w40/in.png" class="mr-2" style="width: 22px; height: 15px; object-fit: cover; border-radius: 2px;">
                                {{ __('Indian Rupee (INR)') }}
                              @else
                                <i class="fas fa-globe mr-2 text-muted" style="font-size: 16px;"></i>
                                {{ $currency->text }}
                              @endif
                            </div>
                          </td>
                          <td>{{ $currency->symbol }}</td>
                          <td>{{ $currency->value }}</td>
                          <td>
                            @if ($currency->is_default == 1)
                              <span class="badge-default-pill">
                                <i class="fas fa-check-circle"></i> {{ __('Default') }}
                              </span>
                            @else
                              -
                            @endif
                          </td>
                          <td>
                            <div class="d-flex align-items-center justify-content-center">
                              <a class="btn-action-edit editbtn" href="#editModal" data-toggle="modal"
                                data-id="{{ $currency->id }}" data-text="{{ $currency->text }}"
                                data-value="{{ $currency->value }}" data-symbol="{{ $currency->symbol }}"
                                data-text_position="{{ $currency->text_position }}"
                                data-symbol_position="{{ $currency->symbol_position }}"
                                title="{{ __('Edit') }}">
                                <i class="fas fa-edit"></i>
                              </a>

                              @if ($currency->is_default != 1)
                                <form class="deleteform d-inline-block" action="{{ route('user-currency-delete') }}" method="post">
                                  @csrf
                                  <input type="hidden" name="currency_id" value="{{ $currency->id }}">
                                  <button type="submit" class="btn-action-delete deletebtn" title="{{ __('Delete') }}">
                                    <i class="fas fa-trash"></i>
                                  </button>
                                </form>

                                <form class="DefaultForm d-inline-block"
                                  action="{{ route('user-currency-status', ['id1' => $currency->id, 'id2' => 1]) }}"
                                  method="post">
                                  @csrf
                                  <input type="hidden" name="currency_id" value="{{ $currency->id }}">
                                  <button type="submit" class="btn-action-more DefaultBtn" title="{{ __('Set Default') }}">
                                    <i class="fas fa-ellipsis-h"></i>
                                  </button>
                                </form>
                              @else
                                <button class="btn-action-more" disabled style="opacity: 0.5;">
                                  <i class="fas fa-ellipsis-h"></i>
                                </button>
                              @endif
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

  <!-- Create Userful Link Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Currency') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="ajaxForm" class="modal-form create" action="{{ route('user-currency-store') }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="">{{ __('Text') }} <span class="text-danger">**</span></label>
              <input type="text" class="form-control" name="text" value=""
                placeholder="{{ __('Enter Text') }}">
              <input type="hidden" class="form-control" name="user_id" value="{{ $user_id }}">
              <p id="errtext" class="mb-0 text-danger em"></p>
            </div>

            <div class="form-group">
              <label for="">{{ __('Symbol') }} <span class="text-danger">**</span></label>
              <input class="form-control" name="symbol" placeholder="{{ __('Enter Symbol') }}">
              <p id="errsymbol" class="mb-0 text-danger em"></p>
            </div>

            <div class="form-group">
              <label for="">{{ __('Text Position') }} <span class="text-danger">**</span></label>
              <select name="text_position" id="" class="form-control">
                <option value="left">{{ __('Left') }}</option>
                <option value="right">{{ __('Right') }}</option>
              </select>
              <p id="errtext_position" class="mb-0 text-danger em"></p>
            </div>

            <div class="form-group">
              <label for="">{{ __('Symbol Position') }} <span class="text-danger">**</span></label>
              <select name="symbol_position" id="" class="form-control">
                <option value="left">{{ __('Left') }}</option>
                <option value="right">{{ __('Right') }}</option>
              </select>
              <p id="errsymbol_position" class="mb-0 text-danger em"></p>
            </div>

            <div class="form-group">
              <label for="">{{ __('Rate') }} <span class="text-danger">**</span></label>
              <input type="number" class="form-control" name="value" placeholder="{{ __('Enter rate') }}">
              <p id="errvalue" class="mb-0 text-danger em"></p>
              <p class="mt-1 mb-0 text-info">
                <strong>{{ __('Please Enter The Rate For 1') }}
                  {{ $default_currency->text }} = ?</strong>
              </p>
            </div>

          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
          <button id="submitBtn" type="button" class="btn btn-primary">{{ __('Submit') }}</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Userful Link Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Currency') }}
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="ajaxEditForm" action="{{ route('user-currency-update') }}" method="POST">
            @csrf
            <input id="inid" type="hidden" name="id" value="">
            <div class="form-group">
              <label for="">{{ __('Text') }} <span class="text-danger">**</span></label>
              <input id="intext" type="text" class="form-control" name="text" value=""
                placeholder="{{ __('Enter text') }}">
              <p id="eerrtext" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Symbol') }} <span class="text-danger">**</span></label>
              <input id="insymbol" class="form-control" name="symbol" placeholder="{{ __('Enter symbol') }}">
              <p id="eerrsymbol" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Text Position') }}</label>
              <select name="text_position" id="intext_position" class="form-control">
                <option value="left">{{ __('Left') }}</option>
                <option value="right">{{ __('Right') }}</option>
              </select>
              <p id="eerrtext_position" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Symbol Position') }}</label>
              <select name="symbol_position" id="insymbol_position" class="form-control">
                <option value="left">{{ __('Left') }}</option>
                <option value="right">{{ __('Right') }}</option>
              </select>
              <p id="eerrsymbol_position" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Rate') }} <span class="text-danger">**</span></label>
              <input type="number" id="invalue" class="form-control" name="value"
                placeholder="{{ __('Enter rate') }}">
              <p id="eerrvalue" class="mb-0 text-danger em"></p>
              <p class="mt-1 mb-0 text-info">
                <strong>{{ __('Please Enter The Rate For 1') }}
                  {{ $default_currency->text }} = ?</strong>
              </p>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
          <button id="updateBtn" type="button" class="btn btn-primary">{{ __('Save Changes') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection

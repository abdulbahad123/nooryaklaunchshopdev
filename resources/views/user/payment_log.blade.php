@extends('user.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Logs') }}</h4>
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
        <a href="#">{{ __('Membership') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Logs') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-4">
              <div class="card-title d-inline-block">{{ __('Logs') }}</div>
            </div>
            <div class="col-lg-3">
            </div>
            <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
              <form action="{{ url()->current() }}" class="d-inline-block float-right">
                <input class="form-control" type="text" name="search"
                  placeholder="{{ __('Search by Transaction ID') }}"
                  value="{{ request()->input('search') ? request()->input('search') : '' }}">
              </form>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($memberships) == 0)
                <h3 class="text-center">{{ __('NO MEMBERSHIP FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3">
                    <thead>
                      <tr>
                        <th scope="col">{{ __('Transaction Id') }}</th>
                        <th scope="col">{{ __('Package') }}</th>
                        <th scope="col">{{ __('Amount') }}</th>
                        <th scope="col">{{ __('Payment Status') }}</th>
                        <th scope="col">{{ __('Payment Method') }}</th>
                        <th scope="col">{{ __('Receipt') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($memberships as $key => $membership)
                        <tr>
                          <td>
                            @php
                              $txId = $membership->transaction_id;
                              $isUpi = strtolower($membership->payment_method) === 'upi' || strtolower($membership->payment_method) === 'offline';
                            @endphp
                            @if($isUpi && !empty($txId))
                              <span class="badge badge-warning text-dark font-weight-bold mr-1" style="font-size: 10px; border-radius: 4px;">UTR</span>
                              <code style="font-size: 12px; font-weight: 700; color: #1e293b;">{{ strlen($txId) > 28 ? mb_substr($txId, 0, 28, 'UTF-8') . '...' : $txId }}</code>
                            @elseif(!empty($txId))
                              <code style="font-size: 12px; font-weight: 700; color: #1e293b;">{{ strlen($txId) > 28 ? mb_substr($txId, 0, 28, 'UTF-8') . '...' : $txId }}</code>
                            @else
                              <span class="text-muted">-</span>
                            @endif
                          </td>
                          <td>
                            @if (!empty($membership->package))
                              {{ __($membership->package->title) }}
                              <span class="badge badge-secondary badge-xs">{{ __($membership->package->term) }}</span>
                            @endif
                          </td>
                          @php
                            $bex = json_decode($membership->settings);
                          @endphp
                          <td>
                            @if ($membership->price == 0)
                              Free
                            @else
                              {{ format_price($membership->price) }}
                            @endif
                          </td>
                          <td>
                            @if ($membership->status == 1)
                              <h3 class="d-inline-block badge badge-success">{{ __('Success') }}
                              </h3>
                            @elseif ($membership->status == 0)
                              <h3 class="d-inline-block badge badge-warning">{{ __('Pending') }}
                              </h3>
                            @elseif ($membership->status == 2)
                              <h3 class="d-inline-block badge badge-danger">{{ __('Rejected') }}
                              </h3>
                            @endif
                          </td>
                          <td>{{ __($membership->payment_method) }}</td>
                          <td>
                            @if (!empty($membership->receipt))
                              <a class="btn btn-sm btn-info text-white font-weight-bold" href="#" data-toggle="modal"
                                data-target="#receiptModal{{ $membership->id }}">
                                <i class="fas fa-file-image mr-1"></i> {{ __('View Receipt') }}
                              </a>
                            @else
                              <a class="btn btn-sm btn-outline-primary font-weight-bold" href="#" data-toggle="modal"
                                data-target="#digitalReceiptModal{{ $membership->id }}">
                                <i class="fas fa-receipt mr-1"></i> {{ __('View Receipt') }}
                              </a>
                            @endif
                          </td>
                          <td>
                            @if (!empty($membership->name !== 'anonymous'))
                              <a class="btn btn-sm btn-info" href="#" data-toggle="modal"
                                data-target="#detailsModal{{ $membership->id }}">{{ __('Details') }}</a>
                            @else
                              -
                            @endif
                          </td>
                        </tr>

                        @if (!empty($membership->receipt))
                        <div class="modal fade" id="receiptModal{{ $membership->id }}" tabindex="-1" role="dialog"
                          aria-labelledby="exampleModalLabel{{ $membership->id }}" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title font-weight-bold" id="exampleModalLabel{{ $membership->id }}">
                                  <i class="fas fa-receipt text-primary mr-1"></i> {{ __('Receipt Image') }}
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body text-center">
                                <img src="{{ asset('assets/front/img/membership/receipt/' . $membership->receipt) }}"
                                  alt="Receipt" class="img-fluid rounded shadow-sm">
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                        @else
                        <div class="modal fade" id="digitalReceiptModal{{ $membership->id }}" tabindex="-1" role="dialog" aria-labelledby="digitalReceiptLabel{{ $membership->id }}" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content" style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                              <div class="modal-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff; padding: 18px 24px;">
                                <div class="d-flex align-items-center" style="gap: 8px;">
                                  <i class="fas fa-receipt" style="font-size: 20px;"></i>
                                  <h5 class="modal-title font-weight-bold mb-0 text-white" id="digitalReceiptLabel{{ $membership->id }}">
                                    {{ __('Official Payment Receipt') }}
                                  </h5>
                                </div>
                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.9;">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body p-4" id="printableReceiptArea{{ $membership->id }}">
                                <div class="text-center mb-4">
                                  <div style="display: inline-flex; width: 56px; height: 56px; border-radius: 50%; background: #ecfdf5; align-items: center; justify-content: center; color: #059669; font-size: 26px; margin-bottom: 10px;">
                                    <i class="fas fa-check-circle"></i>
                                  </div>
                                  <h4 class="font-weight-bold text-dark mb-1">{{ format_price($membership->price) }}</h4>
                                  <span class="badge badge-success px-3 py-1 font-weight-bold" style="border-radius: 20px; font-size: 11.5px; text-transform: uppercase;">
                                    {{ $membership->status == 1 ? __('Payment Successful') : ($membership->status == 0 ? __('Payment Pending') : __('Payment Rejected')) }}
                                  </span>
                                </div>

                                <div style="background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 16px 20px;" class="mb-3">
                                  <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #edf2f7;">
                                    <span class="text-muted small font-weight-bold">{{ __('Payment Method') }}</span>
                                    <span class="font-weight-bold text-dark">{{ ucfirst($membership->payment_method) }}</span>
                                  </div>
                                  <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #edf2f7;">
                                    <span class="text-muted small font-weight-bold">{{ __('Transaction ID / UTR') }}</span>
                                    <code class="font-weight-bold text-primary" style="font-size: 13px;">{{ $membership->transaction_id ?: 'N/A' }}</code>
                                  </div>
                                  <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #edf2f7;">
                                    <span class="text-muted small font-weight-bold">{{ __('Package Plan') }}</span>
                                    <span class="font-weight-bold text-dark">{{ $membership->package->title ?? 'Store Package' }} ({{ ucfirst($membership->package->term ?? 'Yearly') }})</span>
                                  </div>
                                  <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #edf2f7;">
                                    <span class="text-muted small font-weight-bold">{{ __('Payment Date') }}</span>
                                    <span class="font-weight-bold text-dark">{{ \Carbon\Carbon::parse($membership->created_at ?? $membership->start_date)->format('d M, Y h:i A') }}</span>
                                  </div>
                                  <div class="d-flex justify-content-between align-items-center py-2">
                                    <span class="text-muted small font-weight-bold">{{ __('Valid Until') }}</span>
                                    <span class="font-weight-bold text-success">{{ $membership->package && $membership->package->term == 'lifetime' ? __('Lifetime Access') : \Carbon\Carbon::parse($membership->expire_date)->format('d M, Y') }}</span>
                                  </div>
                                </div>

                                <p class="text-center text-muted small mb-0">
                                  <i class="fas fa-shield-alt text-success mr-1"></i> {{ __('Verified digital transaction record generated by LaunchShop.') }}
                                </p>
                              </div>
                              <div class="modal-footer bg-light px-4 py-3 d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary btn-sm font-weight-bold" data-dismiss="modal">
                                  {{ __('Close') }}
                                </button>
                                <button type="button" class="btn btn-primary btn-sm font-weight-bold" onclick="printReceipt('printableReceiptArea{{ $membership->id }}')">
                                  <i class="fas fa-print mr-1"></i> {{ __('Print Receipt') }}
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                        @endif
                        <div class="modal fade" id="detailsModal{{ $membership->id }}" tabindex="-1" role="dialog"
                          aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                  {{ __('Owner Details') }}
                                </h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                <h3 class="text-warning">{{ __('Member details') }}</h3>
                                <label>{{ __('Name') }}</label>
                                <p>{{ $membership->user->shop_name }}</p>
                                <label>{{ __('Email') }}</label>
                                <p>{{ $membership->user->email }}</p>
                                <label>{{ __('Phone') }}</label>
                                <p>{{ $membership->user->phone }}</p>
                                <h3 class="text-warning">{{ __('Payment details') }}</h3>
                                <p><strong>{{ __('Cost') . ':' }} </strong>
                                  {{ $membership->price == 0 ? __('Free') : $membership->price }}
                                </p>
                                <p><strong>{{ __('Currency:') }} </strong>
                                  {{ $membership->currency }}
                                </p>
                                <p><strong>{{ __('Method:') }} </strong>
                                  {{ __($membership->payment_method) }}
                                </p>
                                <h3 class="text-warning">{{ __('Package Details') }}</h3>
                                <p><strong>{{ __('Title') }}:
                                  </strong>{{ __($membership->package->title) }}
                                </p>
                                <p><strong>{{ __('Term') }}: </strong>
                                  {{ __($membership->package->term) }}
                                </p>
                                <p><strong>{{ __('Start Date') }}: </strong>
                                  @if (\Illuminate\Support\Carbon::parse($membership->start_date)->format('Y') == '9999')
                                    <span class="badge badge-danger">{{ __('Never Activated') }}</span>
                                  @else
                                    {{ \Illuminate\Support\Carbon::parse($membership->start_date)->format('M-d-Y') }}
                                  @endif
                                </p>
                                <p><strong>{{ __('Expire Date:') }} </strong>

                                  @if (\Illuminate\Support\Carbon::parse($membership->start_date)->format('Y') == '9999')
                                    -
                                  @else
                                    @if ($membership->modified == 1)
                                      {{ \Illuminate\Support\Carbon::parse($membership->expire_date)->addDay()->format('M-d-Y') }}
                                      <span class="badge badge-primary btn-xs">{{ __('modified by  Admin') }}</span>
                                    @else
                                      {{ $membership->package->term == 'lifetime' ? __('Lifetime') : \Illuminate\Support\Carbon::parse($membership->expire_date)->format('M-d-Y') }}
                                    @endif
                                  @endif
                                </p>
                                <p>
                                  <strong>{{ __('Purchase Type') }}:</strong>
                                  @if ($membership->is_trial == 1)
                                    {{ __('Trial') }}
                                  @else
                                    {{ $membership->price == 0 ? __('Free') : __('Regular') }}
                                  @endif
                                </p>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                  {{ __('Close') }}
                                </button>
                              </div>
                            </div>
                          </div>
                        </div>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="row">
            <div class="d-inline-block mx-auto">
              {{ $memberships->links() }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script>
function printReceipt(divId) {
    var printContent = document.getElementById(divId);
    if (!printContent) return;
    var printWindow = window.open('', '_blank', 'height=650,width=800');
    printWindow.document.write('<!DOCTYPE html><html><head><title>Payment Receipt</title>');
    printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">');
    printWindow.document.write('<style>body{font-family:sans-serif;padding:30px;color:#1e293b;} .modal-header,.modal-footer{display:none!important;}</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(printContent.innerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    setTimeout(function() {
        printWindow.print();
        printWindow.close();
    }, 400);
}
</script>
@endsection

@extends('user.layout')

@section('styles')
  <style>
    @font-face {
      font-family: "Lato-Regular";
      src: url({{ asset('assets/front/fonts/Lato-Regular.ttf') }});
    }
  </style>
@endsection

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('QR Code Builder') }}</h4>
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
        <a href="#">{{ __('QR Code Builder') }}</a>
      </li>
    </ul>
  </div>
  <div class="row">
    <div class="col-lg-7">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">{{ __('Qr Code Generator') }}</h4>
        </div>
        <div class="card-body">
          <form id="qrGeneratorForm" method="POST" enctype="multipart/form-data" onsubmit="return false;">
            @csrf
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  @php
                    $qrUrl = !empty($abs->qr_url) ? $abs->qr_url : url(Auth::user()->username);
                  @endphp
                  <label for="">{{ __('URL') }} <span class="text-danger">**</span></label>
                  <input type="text" class="form-control" name="url" value="{{ $qrUrl }}"
                    onchange="generateQr()">
                  <p class="text-warning mb-0">
                    {{ __('QR Code will be generated for this URL') }}
                  </p>
                </div>
              </div>
              <input type="hidden" name="color" value="000000">
            </div>
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('Size') }}</label>
                  <input class="form-control p-0 range-slider" name="size" type="range" min="200" max="350"
                    value="{{ $abs->qr_size }}" onchange="generateQr()">
                  <span class="text-dark size-text float-right">{{ $abs->qr_size }}</span>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label for="">{{ __('White Space') }}</label>
                  <input class="form-control p-0 range-slider" name="margin" type="range" min="0" max="5"
                    value="{{ $abs->qr_margin }}" onchange="generateQr()">
                  <span class="text-dark size-text float-right">{{ $abs->qr_margin }}</span>
                </div>
              </div>
            </div>

            <input type="hidden" name="style" value="square">
            <input type="hidden" name="eye_style" value="square">
            <input type="hidden" name="type" value="default">
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="card bg-white">
        <div class="card-header border-bottom-ebecec">
          <h4 class="card-title d-inline-block color-575962">{{ __('Preview') }}</h4>
          <button class="btn btn-success float-right" data-toggle="modal"
            data-target="#saveModal">{{ __('Save') }}</button>
          <form action="{{ route('user.qrcode.clear') }}" class="d-inline-block float-right mr-2">
            <button class="btn btn-danger" type="submit">{{ __('Clear') }}</button>
          </form>
        </div>
        <div class="card-body text-center py-5">
          <div class="p-3 border-rounded d-inline-block border background-color-f8f9fa">
            <img id="preview" src="{{ asset('assets/front/img/user/qr/' . $abs->qr_image) }}" alt="">
          </div>
        </div>
        <div class="card-footer text-center border-top-ebecec">
          <a id="downloadBtn" class="btn btn-success" download="qr-image.png"
            href="{{ asset('assets/front/img/user/qr/' . $abs->qr_image) }}">{{ __('Download Image') }}</a>
        </div>
      </div>
      <span id="text-size visibility-hidden">{{ $abs->text }}</span>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="saveModal" tabindex="-1" role="dialog" aria-labelledby="saveModalTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Save QR Code') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{ route('user.qrcode.save') }}" method="POST" id="qrSaveForm">
            @csrf
            <label for="">{{ __('Name') }} <span class="text-danger">**</span></label>
            <input name="name" type="text" class="form-control" required>
            <p class="text-warning mb-0">
              {{ __('This name will be used to identify this specific QR Code from the QR Codes List') }}
            </p>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
          <button type="submit" form="qrSaveForm" class="btn btn-success">{{ __('Save') }}</button>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    'use strict';
    var qr_generate_url = "{{ route('user.qrcode.generate') }}";
  </script>
  <script src="{{ asset('assets/user/js/qr-code-generator.js') }}"></script>
@endsection

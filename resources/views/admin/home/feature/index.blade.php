@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Features') }}</h4>
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
        <a href="#">{{ __('Home Page') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Features') }}</a>
      </li>
    </ul>
  </div>
    <div class="row">
    <div class="col-md-12">

      {{-- Left Side Section Content (Why To Choose Our Template) --}}
      <div class="card mb-4" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
        <div class="card-header bg-light py-3">
          <div class="d-flex align-items-center justify-content-between">
            <h5 class="mb-0 font-weight-bold text-primary">
              <i class="fas fa-edit mr-2"></i> {{ __('Section Content (Left Side - Why Choose Us / Features)') }}
            </h5>
            <span class="badge badge-info">{{ __('Controls About Us & Home Page Feature Headlines') }}</span>
          </div>
        </div>
        <div class="card-body p-4">
          <form action="{{ route('admin.feature.section_update', $lang_id) }}" method="POST">
            @csrf
            <div class="row">
              <div class="col-lg-6 mb-3">
                <label class="font-weight-bold">{{ __('Badge / Tag') }}</label>
                <input type="text" class="form-control" name="features_section_title" 
                       value="{{ @$homeSec->features_section_title ?: 'WHY TO CHOOSE OUR TEMPLATE' }}" 
                       placeholder="e.g. WHY TO CHOOSE OUR TEMPLATE">
              </div>
              <div class="col-lg-6 mb-3">
                <label class="font-weight-bold">{{ __('Main Headline / Title') }}</label>
                <input type="text" class="form-control" name="features_section_subtitle" 
                       value="{{ @$homeSec->features_section_subtitle ?: 'Bring More Profit With More Features' }}" 
                       placeholder="e.g. Bring More Profit With More Features">
              </div>
              <div class="col-lg-12 mb-3">
                <label class="font-weight-bold">{{ __('Description Text') }}</label>
                <textarea class="form-control" name="features_section_text" rows="3" 
                          placeholder="e.g. With our platform, you get powerful tools, seamless integrations...">{{ @$homeSec->features_section_text ?: 'With our platform, you get powerful tools, seamless integrations, and top-notch support to grow your business. We offer reliable performance, user-friendly features, and the flexibility to scale as you do. Choose us for a partner in your success.' }}</textarea>
              </div>
              <div class="col-lg-6 mb-3">
                <label class="font-weight-bold">{{ __('Button Text') }}</label>
                <input type="text" class="form-control" name="features_section_btn_text" 
                       value="{{ @$homeSec->features_section_btn_text ?: 'Signup Now' }}" 
                       placeholder="e.g. Signup Now">
              </div>
              <div class="col-lg-6 mb-3">
                <label class="font-weight-bold">{{ __('Button URL') }}</label>
                <input type="text" class="form-control" name="features_section_btn_url" 
                       value="{{ @$homeSec->features_section_btn_url ?: url('/pricing') }}" 
                       placeholder="e.g. /pricing">
              </div>
            </div>
            <div class="text-right">
              <button type="submit" class="btn btn-success font-weight-bold px-4">
                <i class="fas fa-check mr-1"></i> {{ __('Save Left Side Content') }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-lg-5">
              <div class="card-title d-inline-block">{{ __('Features') }}</div>
            </div>
            <div class="col-lg-2">
              @include('admin.partials.languages')
            </div>
            <div class="col-lg-5 mt-2 mt-lg-0">
              <a href="#" class="btn btn-primary btn-sm float-right" data-toggle="modal"
                data-target="#createModal"><i class="fas fa-plus"></i> {{ __('Add Feature') }}</a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-12">
              @if (count($features) == 0)
                <h3 class="text-center">{{ __('NO FEATURE FOUND') }}</h3>
              @else
                <div class="table-responsive">
                  <table class="table table-striped mt-3" id="basic-datatables">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">{{ __('Image') }}</th>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Text') }}</th>
                        <th scope="col">{{ __('Serial Number') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($features as $key => $feature)
                        <tr>
                          <td>{{ $loop->iteration }}</td>

                          <td><img src="{{ asset('assets/front/img/feature/' . $feature->icon) }}" alt=""
                              width="50"></td>
                          <td>{{ $feature->title }}</td>
                          <td>{{ $feature->text }}</td>
                          <td>{{ $feature->serial_number }}</td>
                          <td>
                            <a class="btn btn-secondary btn-sm mb-1"
                              href="{{ route('admin.feature.edit', $feature->id) . '?language=' . request()->input('language') }}">
                              <span class="btn-label">
                                <i class="fas fa-edit"></i>
                              </span>

                            </a>
                            <form class="deleteform d-inline-block" action="{{ route('admin.feature.delete') }}"
                              method="post">
                              @csrf
                              <input type="hidden" name="feature_id" value="{{ $feature->id }}">
                              <button type="submit" class="btn btn-danger btn-sm deletebtn mb-1">
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
      </div>
    </div>
  </div>


  <!-- Create Feature Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Feature') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="ajaxForm" class="modal-form" action="{{ route('admin.feature.store') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <label for="">{{ __('Language') }} <span class="text-danger">**</span></label>
              <select name="language_id" class="form-control">
                <option value="" selected disabled>{{ __('Select a Language') }}</option>
                @foreach ($langs as $lang)
                  <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                @endforeach
              </select>
              <p id="errlanguage_id" class="mb-0 text-danger em"></p>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <div class="col-12 mb-2 pl-0 pr-0">
                    <label for="image"><strong> {{ __('Feature Image') }} <span
                          class="text-danger">**</span></strong></label>
                  </div>
                  <div class="col-md-12 showImage mb-3 pl-0 pr-0">
                    <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..." class="img-thumbnail">
                  </div>
<br>
                  <div role="button" class="btn btn-primary btn-sm upload-btn" id="image">
                    {{ __('Choose Image') }}
                    <input type="file" class="img-input" name="image">
                  </div>
                  <p id="errimage" class="mb-0 text-danger em"></p>
                  <p class="p-0 text-warning">
                    {{ __('Recommended Image size : 62X62') }}
                  </p>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label for="">{{ __('Title') }} <span class="text-danger">**</span></label>
              <input class="form-control" name="title" placeholder="{{ __('Enter title') }}">
              <p id="errtitle" class="mb-0 text-danger em"></p>
            </div>
            <div class="form-group">
              <label for="">{{ __('Text') }}<span class="text-danger">**</span></label>
              <textarea class="form-control" name="text" placeholder="{{ __('Enter text') }}" rows="5"></textarea>
              <p id="errtext" class="mb-0 text-danger em"></p>
            </div>

            <div class="form-group">
              <label for="">{{ __('Serial Number') }} <span class="text-danger">**</span></label>
              <input type="number" class="form-control ltr" name="serial_number" value=""
                placeholder="{{ __('Enter Serial Number') }}">
              <p id="errserial_number" class="mb-0 text-danger em"></p>
              <p class="text-warning">
                <small>{{ __('The higher the serial number is, the later the feature will be shown.') }}</small>
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

@endsection

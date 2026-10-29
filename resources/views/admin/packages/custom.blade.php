@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Custom Package & Pricing Callouts') }}</h4>
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
        <a href="#">{{ __('Package Management') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Custom Package') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <form id="customPackageForm" action="{{ route('admin.package.custom.update') }}" method="POST">
        @csrf

        {{-- Enterprise Package Section --}}
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-primary text-white d-flex align-items-center justify-content-between">
            <div class="card-title text-white mb-0 font-weight-bold">
              <i class="fas fa-building mr-2"></i> {{ __('Enterprise Package Card Settings') }}
            </div>
            <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" id="enterprise_status" name="enterprise_status" value="1" {{ ($customPackage->enterprise_status ?? 1) ? 'checked' : '' }}>
              <label class="custom-control-label text-white font-weight-bold" for="enterprise_status">{{ __('Show Enterprise Card on Pricing Page') }}</label>
            </div>
          </div>
          <div class="card-body pt-4 pb-4">
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="font-weight-bold">{{ __('Package Title') }} <span class="text-danger">*</span></label>
                  <input type="text" name="enterprise_title" class="form-control" value="{{ $customPackage->enterprise_title ?? 'Enterprise' }}" required placeholder="e.g. Enterprise">
                  <small class="text-muted">{{ __('Displays as the main heading of the 4th plan card.') }}</small>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="font-weight-bold">{{ __('Package Subtitle') }}</label>
                  <input type="text" name="enterprise_subtitle" class="form-control" value="{{ $customPackage->enterprise_subtitle ?? 'Kickstart Your E-commerce Business.' }}" placeholder="e.g. Kickstart Your E-commerce Business.">
                  <small class="text-muted">{{ __('Sub-heading text under the plan title.') }}</small>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="font-weight-bold">{{ __('Price Display Text') }} <span class="text-danger">*</span></label>
                  <input type="text" name="enterprise_price" class="form-control" value="{{ $customPackage->enterprise_price ?? 'Custom' }}" required placeholder="e.g. Custom or Contact Us">
                  <small class="text-muted">{{ __('Displayed in large bold font in place of price amount.') }}</small>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="font-weight-bold">{{ __('Billing Note / Frequency') }}</label>
                  <input type="text" name="enterprise_billing_note" class="form-control" value="{{ $customPackage->enterprise_billing_note ?? 'Tailored to your needs' }}" placeholder="e.g. Tailored to your needs">
                  <small class="text-muted">{{ __('Small note directly below the price text.') }}</small>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label class="font-weight-bold">{{ __('Enterprise Features Checklist (One per line)') }}</label>
                  <textarea name="enterprise_features" rows="7" class="form-control" placeholder="Enter one feature per line...">{{ $customPackage->enterprise_features ?? "Free .com Domain Every Year\nEverything in Premium\nUnlimited Staff Accounts\nDedicated Account Manager\nCustom Integrations\nSLA & Uptime Guarantee\nPriority 24/7 Support" }}</textarea>
                  <small class="text-info font-weight-bold">
                    <i class="fas fa-info-circle mr-1"></i> {{ __('Tip: Features mentioning "Domain" or "Free" will automatically be rendered with a green globe icon.') }}
                  </small>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="font-weight-bold">{{ __('Primary Button Text') }}</label>
                  <input type="text" name="enterprise_button_text" class="form-control" value="{{ $customPackage->enterprise_button_text ?? 'Talk to Sales' }}" placeholder="e.g. Talk to Sales">
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="font-weight-bold">{{ __('Primary Button URL / Link') }}</label>
                  <input type="text" name="enterprise_button_url" class="form-control" value="{{ $customPackage->enterprise_button_url ?? '/contact' }}" placeholder="e.g. /contact or full URL">
                </div>
              </div>
            </div>

            {{-- WhatsApp Sales Integration --}}
            <div class="p-3 mt-3 rounded bg-light border border-success">
              <h5 class="text-success font-weight-bold mb-3">
                <i class="fab fa-whatsapp text-success mr-1"></i> {{ __('WhatsApp Enquiry Configuration') }}
              </h5>
              <div class="row">
                <div class="col-lg-5">
                  <div class="form-group p-0 mb-3">
                    <label class="font-weight-bold">{{ __('WhatsApp Sales Number (with country code)') }} <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text bg-success text-white"><i class="fab fa-whatsapp"></i></span>
                      </div>
                      <input type="text" name="enterprise_whatsapp_number" id="wa_number" class="form-control font-weight-bold" value="{{ $customPackage->enterprise_whatsapp_number ?? '917200770351' }}" placeholder="e.g. 917200770351">
                    </div>
                    <small class="text-muted">{{ __('Do not include + or spaces (e.g. 917200770351).') }}</small>
                  </div>
                </div>
                <div class="col-lg-7">
                  <div class="form-group p-0 mb-3">
                    <label class="font-weight-bold">{{ __('WhatsApp Pre-filled Message Template') }} <span class="text-danger">*</span></label>
                    <textarea name="enterprise_whatsapp_message" id="wa_msg" rows="2" class="form-control" placeholder="e.g. Hi, I want to enquire about the Enterprise Plan for Ecom Builder.">{{ $customPackage->enterprise_whatsapp_message ?? 'Hi, I want to enquire about the Enterprise Plan for Ecom Builder.' }}</textarea>
                    <small class="text-muted">{{ __('The message that opens automatically in the user\'s WhatsApp.') }}</small>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        {{-- Callout Containers Section --}}
        <div class="card shadow-sm border-0 mb-4">
          <div class="card-header bg-dark text-white font-weight-bold">
            <i class="fas fa-tags mr-2 text-warning"></i> {{ __('Pricing Callouts (Most Popular & Most People Choice Containers)') }}
          </div>
          <div class="card-body pt-4 pb-4">
            
            {{-- Most Popular Callout --}}
            <div class="p-3 mb-4 rounded border border-warning bg-light">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <h5 class="text-warning font-weight-bold mb-0">
                  <i class="fas fa-rocket mr-1 text-danger"></i> {{ __('"Most Popular" Callout Container') }}
                </h5>
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="most_popular_status" name="most_popular_status" value="1" {{ ($customPackage->most_popular_status ?? 1) ? 'checked' : '' }}>
                  <label class="custom-control-label font-weight-bold" for="most_popular_status">{{ __('Enable Callout') }}</label>
                </div>
              </div>
              <small class="text-muted d-block mb-3">{{ __('Callout box hovering with curved arrow pointing to Ecom Standard plan.') }}</small>

              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group p-0 mb-2">
                    <label class="font-weight-bold">{{ __('Callout Badge Title') }}</label>
                    <input type="text" name="most_popular_title" class="form-control font-weight-bold" value="{{ $customPackage->most_popular_title ?? 'Most Popular' }}" placeholder="e.g. Most Popular">
                  </div>
                </div>
                <div class="col-lg-8">
                  <div class="form-group p-0 mb-2">
                    <label class="font-weight-bold">{{ __('Callout Description') }}</label>
                    <input type="text" name="most_popular_desc" class="form-control" value="{{ $customPackage->most_popular_desc ?? 'Ideal balance for growing businesses with all essential tools.' }}" placeholder="e.g. Ideal balance for growing businesses with all essential tools.">
                  </div>
                </div>
              </div>
            </div>

            {{-- Most People Choice Callout --}}
            <div class="p-3 rounded border border-warning bg-light">
              <div class="d-flex align-items-center justify-content-between mb-2">
                <h5 class="text-warning font-weight-bold mb-0">
                  <i class="fas fa-star mr-1 text-warning"></i> {{ __('"Most People Choice" Callout Container') }}
                </h5>
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="most_people_choice_status" name="most_people_choice_status" value="1" {{ ($customPackage->most_people_choice_status ?? 1) ? 'checked' : '' }}>
                  <label class="custom-control-label font-weight-bold" for="most_people_choice_status">{{ __('Enable Callout') }}</label>
                </div>
              </div>
              <small class="text-muted d-block mb-3">{{ __('Callout box hovering with curved arrow pointing to Enterprise / Premium plan.') }}</small>

              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group p-0 mb-2">
                    <label class="font-weight-bold">{{ __('Callout Badge Title') }}</label>
                    <input type="text" name="most_people_choice_title" class="form-control font-weight-bold" value="{{ $customPackage->most_people_choice_title ?? 'Most People Choice' }}" placeholder="e.g. Most People Choice">
                  </div>
                </div>
                <div class="col-lg-8">
                  <div class="form-group p-0 mb-2">
                    <label class="font-weight-bold">{{ __('Callout Description') }}</label>
                    <input type="text" name="most_people_choice_desc" class="form-control" value="{{ $customPackage->most_people_choice_desc ?? 'Best value with all premium features and a free domain.' }}" placeholder="e.g. Best value with all premium features and a free domain.">
                  </div>
                </div>
              </div>
            </div>

          </div>
          <div class="card-footer bg-white border-top text-center py-3">
            <button type="submit" class="btn btn-success btn-lg px-5 font-weight-bold shadow-sm">
              <i class="fas fa-check-circle mr-1"></i> {{ __('Save & Update Custom Package Settings') }}
            </button>
          </div>
        </div>

      </form>
    </div>
  </div>
@endsection
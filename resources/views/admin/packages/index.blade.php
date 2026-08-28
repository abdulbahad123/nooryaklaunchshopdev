@extends('admin.layout')

@php
    use App\Models\Language;
    $selLang = Language::where('code', request()->input('language'))->first();
@endphp
@if (!empty($selLang) && $selLang->rtl == 1)
    @section('styles')
        <link rel="stylesheet" href="{{ asset('assets/admin/css/rtl.css') }}">
    @endsection
@endif

@section('content')
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h4 class="page-title d-flex align-items-center gap-2">
                {{ __('Packages') }}
            </h4>
            <ul class="breadcrumbs m-0">
                <li class="nav-home">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="fas fa-chevron-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">{{ __('Package Management') }}</a>
                </li>
                <li class="separator">
                    <i class="fas fa-chevron-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">{{ __('Packages') }}</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0" style="border-radius: 20px; box-shadow: var(--shadow-card);">
                <div class="card-header border-0 pb-0 pt-4 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="card-title m-0 font-weight-bold" style="font-size: 1.25rem;">{{ __('Packages') }}</h3>
                    </div>
                    <div>
                        <a href="#" class="btn-primary-purple m-0 py-2 px-3" style="font-size: 0.85rem; border-radius: 10px; text-decoration: none;" data-toggle="modal" data-target="#createModal">
                            <i class="fas fa-plus mr-1"></i> {{ __('Add Package') }}
                        </a>
                        <button class="btn btn-danger btn-sm rounded-pill ml-2 d-none bulk-delete"
                            data-href="{{ route('admin.package.bulk.delete') }}">
                            <i class="fas fa-trash mr-1"></i> {{ __('Delete') }}
                        </button>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($packages) == 0)
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-box-open" style="font-size: 48px; opacity: 0.5;"></i>
                                    <h4 class="mt-3 font-weight-bold">{{ __('NO PACKAGE FOUND') }}</h4>
                                </div>
                            @else
                                <div class="table-responsive" style="overflow-x: auto; width: 100%;">
                                    <table class="table table-hover align-middle" id="basic-datatables" style="white-space: nowrap !important;">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="width: 40px;">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ __('Title') }}</th>
                                                <th scope="col">{{ __('Cost') }}</th>
                                                <th scope="col" style="width: 120px;">{{ __('Status') }}</th>
                                                <th scope="col" class="text-right" style="width: 110px;">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($packages as $key => $package)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $package->id }}">
                                                    </td>
                                                    <td class="font-weight-bold text-dark" style="font-size: 0.875rem;">
                                                        {{ truncateString(__($package->title), 30) }}
                                                    </td>
                                                    <td class="font-weight-bold" style="font-size: 0.875rem;">
                                                        @if ($package->price == 0)
                                                            {{ __('Free') }}
                                                        @else
                                                            {{ format_price($package->price) }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($package->status == 1)
                                                            <span class="status-pill-active">
                                                                <i class="fas fa-circle" style="font-size: 0.45rem;"></i> {{ __('Active') }}
                                                            </span>
                                                        @else
                                                            <span class="status-pill-deactive">
                                                                <i class="fas fa-circle" style="font-size: 0.45rem;"></i> {{ __('Deactive') }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-right">
                                                        <div class="d-inline-flex align-items-center gap-2">
                                                            <a class="btn-action-square b-edit"
                                                                href="{{ route('admin.package.edit', $package->id) . '?language=' . request()->input('language') }}" title="{{ __('Edit') }}">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </a>
                                                            <form class="deleteform d-inline-block m-0"
                                                                action="{{ route('admin.package.delete') }}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="package_id"
                                                                    value="{{ $package->id }}">
                                                                <button type="submit"
                                                                    class="btn-action-square b-delete deletebtn" title="{{ __('Delete') }}">
                                                                    <i class="fas fa-trash-alt"></i>
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
    <!-- Create Blog Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Package') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form id="ajaxForm" enctype="multipart/form-data" class="modal-form"
                        action="{{ route('admin.package.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">{{ __('Package title') }} <span
                                            class="text-danger">**</span></label>
                                    <input id="title" type="text" class="form-control" name="title"
                                        placeholder="{{ __('Enter Package title') }}" value="">
                                    <p id="errtitle" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price">{{ __('Price') }} ({{ $bex->base_currency_text }}) <span
                                            class="text-danger">**</span></label>
                                    <input id="price" type="number" class="form-control" name="price"
                                        placeholder="{{ __('Enter Package price') }}" value="">
                                    <p class="text-warning mb-0">
                                        <small>{{ __('If price is 0 , than it will appear as free') }}</small>
                                    </p>
                                    <p id="errprice" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="term">{{ __('Package term') }} <span
                                            class="text-danger">**</span></label>
                                    <select id="term" name="term" class="form-control" required>
                                        <option value="" selected disabled>{{ __('Choose a Package term') }}
                                        </option>
                                        <option value="monthly">{{ __('monthly') }}</option>
                                        <option value="yearly">{{ __('yearly') }}</option>
                                    </select>
                                    <p id="errterm" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                            @php
                                if (\Schema::hasTable('package_features')) {
                                    $allFeatures = \App\Models\PackageFeature::whereIn('type', ['standard', 'custom'])
                                        ->whereNotIn('name', ['Disqus', 'Bank Transfer Integrations'])
                                        ->whereNotIn('keyword', ['Disqus', 'Bank Transfer Integrations'])
                                        ->orderBy('serial_number', 'asc')
                                        ->get();
                                } else {
                                    $allFeatures = collect();
                                }
                            @endphp
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Package Features') }}</label>
                                    <div class="selectgroup selectgroup-pills">
                                        @if($allFeatures->isEmpty())
                                            @php
                                                $fallbackFeatures = [
                                                    'Custom Domain' => 'Custom Domain',
                                                    'Subdomain' => 'Subdomain',
                                                    'QR Builder' => 'QR Builder',
                                                    'Blog' => 'Blog',
                                                    'Custom Page' => 'Custom Page',
                                                    'Google Login' => 'Google Login',
                                                    'Google Analytics' => 'Google Analytics',
                                                    'Google Recaptcha' => 'Google Recaptcha',
                                                    'WhatsApp Chat Button' => 'WhatsApp Chat Button',
                                                    'Tawk to' => 'Tawk to',
                                                    'AI Content & Image Generator' => 'AI Content & Image Generator'
                                                ];
                                            @endphp
                                            @foreach($fallbackFeatures as $k => $name)
                                                @if($k === 'AI Content & Image Generator') @continue @endif
                                                <label class="selectgroup-item">
                                                    <input type="checkbox" name="features[]" value="{{ $k }}"
                                                        class="selectgroup-input">
                                                    <span class="selectgroup-button">{{ __($name) }}</span>
                                                </label>
                                            @endforeach
                                        @else
                                            @foreach($allFeatures as $feature)
                                                @if($feature->keyword === 'AI Content & Image Generator' || $feature->name === 'AI Content & Image Generator') @continue @endif
                                                @php
                                                    $val = $feature->keyword ?: $feature->name;
                                                @endphp
                                                <label class="selectgroup-item">
                                                    <input type="checkbox" name="features[]" value="{{ $val }}"
                                                        class="selectgroup-input">
                                                    <span class="selectgroup-button">{{ __($feature->name) }}</span>
                                                </label>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Featured') }} <span
                                            class="text-danger">**</span></label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="featured" value="1"
                                                class="selectgroup-input">
                                            <span class="selectgroup-button">{{ __('Yes') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="featured" value="0"
                                                class="selectgroup-input" checked>
                                            <span class="selectgroup-button">{{ __('No') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Popular') }} <span
                                            class="text-danger">**</span></label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="recommended" value="1"
                                                class="selectgroup-input">
                                            <span class="selectgroup-button">{{ __('Yes') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="recommended" value="0"
                                                class="selectgroup-input" checked>
                                            <span class="selectgroup-button">{{ __('No') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">{{ __('Trial') }} <span
                                            class="text-danger">**</span></label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="is_trial" value="1"
                                                class="selectgroup-input">
                                            <span class="selectgroup-button">{{ __('Yes') }}</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="is_trial" value="0"
                                                class="selectgroup-input" checked>
                                            <span class="selectgroup-button">{{ __('No') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">{{ __('Icon') }} <span class="text-danger">**</span></label>
                                    <div class="btn-group d-block">
                                        <button type="button" class="btn btn-primary iconpicker-component"><i
                                                class="fa fa-fw fa-heart"></i></button>
                                        <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle"
                                            data-selected="fa-car" data-toggle="dropdown">
                                        </button>
                                        <div class="dropdown-menu"></div>
                                    </div>
                                    <input id="inputIcon" type="hidden" name="icon" value="fas fa-heart">
                                    @if ($errors->has('icon'))
                                        <p class="mb-0 text-danger">{{ $errors->first('icon') }}</p>
                                    @endif
                                    <div class="mt-2">
                                        <small>{{ __('NB: click on the dropdown sign to select a icon.') }}</small>
                                    </div>
                                    <p id="erricon" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                            @php
                                $d_none = 'none';
                                $d_block = 'block';
                            @endphp
                            <div class="col-md-6" id="trial_day" style="display: {{ $d_none }}">
                                <div class="form-group">
                                    <label for="trial_days">{{ __('Trial days') }} <span
                                            class="text-danger">**</span></label>
                                    <input id="trial_days" type="number" class="form-control" name="trial_days"
                                        placeholder="{{ __('Enter trial days') }}" value="">
                                    <p id="errtrial_days" class="mb-0 text-danger em"></p>
                                    <p class="text-warning">{{ __('999999 count as Unlimited') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6"id="post_limit" style="display: {{ $d_none }}">
                                <div class="form-group">
                                    <label for="post_limit">{{ __('Blog Post Limit') }} <span
                                            class="text-danger">**</span></label>
                                    <input id="post_limit" type="number" class="form-control" name="post_limit"
                                        placeholder="{{ __('Enter Blog Post Limit') }}" value="">
                                    <p id="errpost_limit" class="mb-0 text-danger em"></p>
                                    <p class="text-warning">{{ __('999999 count as Unlimited') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6" id="product_limit" style="display: {{ $d_block }}">
                                <div class="form-group">
                                    <label for="product_limit">{{ __('Product Limit') }} <span
                                            class="text-danger">**</span></label>
                                    <input id="product_limit" type="number" class="form-control" name="product_limit"
                                        placeholder="{{ __('Enter Product Limit') }}" value="">
                                    <p id="errproduct_limit" class="mb-0 text-danger em"></p>
                                    <p class="text-warning">{{ __('999999 count as Unlimited') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6" id="categories_limit" style="display: {{ $d_block }}">
                                <div class="form-group">
                                    <label for="categories_limit">{{ __('Categories Limit') }} <span
                                            class="text-danger">**</span></label>
                                    <input id="categories_limit" type="number" class="form-control"
                                        name="categories_limit" placeholder="{{ __('Enter Categories Limit') }}"
                                        value="">
                                    <p id="errcategories_limit" class="mb-0 text-danger em"></p>
                                    <p class="text-warning">{{ __('999999 count as Unlimited') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6" id="subcategories_limit" style="display: {{ $d_block }}">
                                <div class="form-group">
                                    <label for="subcategories_limit">{{ __('Subcategories Limit') }} <span
                                            class="text-danger">**</span></label>
                                    <input id="subcategories_limit" type="number" class="form-control"
                                        name="subcategories_limit" placeholder="{{ __('Enter SubCategories Limit') }}"
                                        value="">
                                    <p id="errsubcategories_limit" class="mb-0 text-danger em"></p>
                                    <p class="text-warning">{{ __('999999 count as Unlimited') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6" id="order_limit" style="display: {{ $d_block }}">
                                <div class="form-group">
                                    <label for="order_limit">{{ __('Order Limit') }} <span
                                            class="text-danger">**</span></label>
                                    <input id="order_limit" type="number" class="form-control" name="order_limit"
                                        placeholder="{{ __('Enter Order Limit') }}" value="">
                                    <p id="errorder_limit" class="mb-0 text-danger em"></p>
                                    <p class="text-warning">{{ __('999999 count as Unlimited') }}</p>
                                </div>
                            </div>

                            <div class="col-md-6 custom-page-box d-none">
                                <div class="form-group">
                                    <label for="">{{ __('Number of Custom Page') }} <span
                                            class="text-danger">**</span></label>
                                    <input type="number" class="form-control" name="number_of_custom_page"
                                        placeholder="{{ __('Enter Custom Page Limit') }}" value="">
                                    <p id="errnumber_of_custom_page" class="mb-0 text-danger em"></p>
                                    <p class="text-warning">{{ __('Enter 999999 , then it will appear as unlimited') }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6" id="language_limit" style="display: {{ $d_block }}">
                                <div class="form-group">
                                    <label for="language_limit">{{ __('Additional Language Limit') }} <span
                                            class="text-danger">**</span></label>
                                    <input id="language_limit" type="number" class="form-control" name="language_limit"
                                        placeholder="{{ __('Enter Additional Language Limit') }}" value="">
                                    <p id="errlanguage_limit" class="mb-0 text-danger em"></p>
                                    <p class="text-warning">{{ __('999999 count as Unlimited') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 ai-feature-box d-none">
                                <div class="form-group">
                                    <label for="ai_engine">{{ __('AI Engine') }} <span
                                            class="text-danger">**</span></label>
                                    <select id="ai_engine" name="ai_engine" class="form-control">
                                        <option value="" selected disabled>{{ __('Choose AI Engine') }}</option>
                                        <option value="gemini">{{ __('Gemini') }}</option>
                                        <option value="openai">{{ __('OpenAI') }}</option>
                                    </select>
                                    <p id="errai_engine" class="mb-0 text-danger em"></p>
                                    <p class="text-info mb-0">
                                        {{'*' . __('Select the AI engine that will power content and image generation for this package') . '.' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 ai-feature-box d-none">
                                <div class="form-group">
                                    <label for="ai_token_limit">{{ __('Total AI Token Limit') }} <span
                                            class="text-danger">**</span></label>
                                    <input id="ai_token_limit" type="number" class="form-control" name="ai_token_limit"
                                        placeholder="{{ __('Enter Total AI Token Limit') }}" value="">
                                    <p id="errai_token_limit" class="mb-0 text-danger em"></p>
                                    <p class="text-warning">{{ __('999999 count as Unlimited') }}</p>
                                    <p class="text-info mb-0">
                                        {{'*' . __('Defines the total AI token usage allowed for this package for generating content') . '.' }}
                                    </p>
                                    <p class="text-warning mt-2">
                                        {{'*' . __('Minimum 20,000 tokens may be required for content generation') . '. ' .__('English content is usually cheaper, while other languages may consume more tokens and cost higher') . '. ' . __('For reference, 1K token approximately equal to USD 0.0015') . '.' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 ai-feature-box d-none">
                                <div class="form-group">
                                    <label for="ai_image_limit">{{ __('Total AI Image Limit') }} <span
                                            class="text-danger">**</span></label>
                                    <input id="ai_image_limit" type="number" class="form-control" name="ai_image_limit"
                                        placeholder="{{ __('Enter Total AI Image Limit') }}" value="">
                                    <p id="errai_image_limit" class="mb-0 text-danger em"></p>
                                    <p class="text-warning">{{ __('999999 count as Unlimited') }}</p>
                                    <p class="text-info mb-0">
                                        {{'*' . __('This defines how many AI-generated images a tenant user can create under this package') . '.' }}
                                    </p>
                                    <p class="text-warning mb-0">
                                        {{'*' . __('Each AI image generation approximately costs USD 0.04 per image') . '.' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">{{ __('Status') }} <span
                                            class="text-danger">**</span></label>
                                    <select id="status" class="form-control ltr" name="status">
                                        <option value="" selected disabled>{{ __('Select a status') }}</option>
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Deactive') }}</option>
                                    </select>
                                    <p id="errstatus" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">{{ __('Meta Keywords') }}</label>
                                    <input type="text" class="form-control" name="meta_keywords" value=""
                                        data-role="tagsinput">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="meta_description">{{ __('Meta Description') }}</label>
                                    <textarea id="meta_description" type="text" class="form-control" name="meta_description" rows="5"></textarea>
                                </div>
                            </div>
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

@section('scripts')
    <script src="{{ asset('assets/admin/js/packages.js') }}"></script>
@endsection

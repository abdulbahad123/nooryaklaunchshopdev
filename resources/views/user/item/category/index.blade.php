@extends('user.layout')

@php
    $userDefaultLang = \App\Models\User\Language::where([
        ['user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id],
        ['is_default', 1],
    ])->first();
    $userLanguages = \App\Models\User\Language::where(
        'user_id',
        \Illuminate\Support\Facades\Auth::guard('web')->user()->id,
    )->get();
@endphp

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Categories') }}</h4>
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
                <a href="#">{{ __('Categories') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header border-0 pb-0 pt-4 px-4 bg-transparent d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex align-items-center mb-2 mb-lg-0">
                        <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 44px; height: 44px; border-radius: 12px; background: rgba(13, 110, 253, 0.1);">
                            <i class="fas fa-th-large text-primary" style="font-size: 20px;"></i>
                        </span>
                        <div>
                            <h4 class="card-title font-weight-bold mb-1" style="font-size: 18px;">{{ __('Categories') }}</h4>
                            <p class="text-muted mb-0" style="font-size: 13px;">{{ __('Manage your product categories') }}</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        @if (!is_null($userDefaultLang))
                            @if (!empty($userLanguages))
                                <select name="userLanguage" class="form-control form-control-sm mr-2" style="border-radius: 8px; width: auto; height: 36px;"
                                    onchange="window.location='{{ url()->current() . '?language=' }}'+this.value">
                                    <option value="" selected disabled>{{ __('Select a Language') }}</option>
                                    @foreach ($userLanguages as $lang)
                                        <option value="{{ $lang->code }}" {{ $lang->code == request()->input('language') ? 'selected' : '' }}>
                                            {{ $lang->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        @endif
                        <a href="#" class="btn btn-primary btn-sm font-weight-bold d-inline-flex align-items-center" style="border-radius: 8px; padding: 8px 16px; height: 36px;" data-toggle="modal" data-target="#createModal">
                            <i class="fas fa-plus mr-2"></i> {{ __('Add Category') }}
                        </a>
                        <button class="btn btn-danger btn-sm font-weight-bold mr-2 d-none bulk-delete" style="border-radius: 8px; height: 36px;" data-href="{{ route('user.itemcategory.bulk.delete') }}">
                            <i class="flaticon-interface-5 mr-1"></i> {{ __('Delete') }}
                        </button>
                    </div>
                </div>
                @php
                    $allow_featured_theme = ['electronics', 'kids', 'manti', 'pet', 'skinflow', 'jewellery'];
                    $icons = [
                        ['bg' => 'rgba(13, 110, 253, 0.1)', 'color' => '#0d6efd', 'icon' => 'fas fa-mobile-alt'],
                        ['bg' => 'rgba(40, 167, 69, 0.1)', 'color' => '#28a745', 'icon' => 'fas fa-home'],
                        ['bg' => 'rgba(253, 126, 20, 0.1)', 'color' => '#fd7e14', 'icon' => 'fas fa-tshirt'],
                        ['bg' => 'rgba(111, 66, 193, 0.1)', 'color' => '#6f42c1', 'icon' => 'fas fa-headphones'],
                        ['bg' => 'rgba(232, 62, 140, 0.1)', 'color' => '#e83e8c', 'icon' => 'fas fa-couch'],
                        ['bg' => 'rgba(23, 162, 184, 0.1)', 'color' => '#17a2b8', 'icon' => 'fas fa-box'],
                    ];
                @endphp
                <div class="card-body px-4 pt-3 pb-4">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($itemcategories) == 0)
                                <h3 class="text-center py-5">{{ __('NO CATEGORY FOUND') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover mt-3" style="border-collapse: separate; border-spacing: 0 8px;">
                                        <thead>
                                            <tr style="background: rgba(0,0,0,0.02);">
                                                <th scope="col" class="border-top-0 border-bottom-0" style="border-radius: 8px 0 0 8px; width: 40px;">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col" class="border-top-0 border-bottom-0 font-weight-bold">{{ __('Name') }}</th>
                                                <th scope="col" class="border-top-0 border-bottom-0 font-weight-bold">{{ __('Image') }}</th>
                                                @if (in_array($userBs->theme, $allow_featured_theme))
                                                    <th scope="col" class="border-top-0 border-bottom-0 font-weight-bold">{{ __('Featured') }}</th>
                                                @endif
                                                <th scope="col" class="border-top-0 border-bottom-0 font-weight-bold">{{ __('Status') }}</th>
                                                <th scope="col" class="border-top-0 border-bottom-0 font-weight-bold">{{ __('Serial Number') }}</th>
                                                <th scope="col" class="border-top-0 border-bottom-0 font-weight-bold" style="border-radius: 0 8px 8px 0;">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($itemcategories as $key => $category)
                                                @php
                                                    $iconStyle = $icons[$key % count($icons)];
                                                @endphp
                                                <tr style="background: #ffffff; box-shadow: 0 2px 5px rgba(0,0,0,0.02); border-radius: 8px;">
                                                    <td class="align-middle" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04); border-left: 1px solid rgba(0,0,0,0.04); border-radius: 8px 0 0 8px;">
                                                        <input type="checkbox" class="bulk-check" data-val="{{ $category->id }}">
                                                    </td>
                                                    <td class="align-middle font-weight-bold" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04);">
                                                        <div class="d-flex align-items-center">
                                                            <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px; border-radius: 10px; background: {{ $iconStyle['bg'] }}; color: {{ $iconStyle['color'] }}; flex-shrink: 0;">
                                                                <i class="{{ $iconStyle['icon'] }}" style="font-size: 16px;"></i>
                                                            </span>
                                                            <span style="font-size: 14px;">{{ convertUtf8($category->name) }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04);">
                                                        <img src="{{ $category->image ? asset('assets/front/img/user/items/categories/' . $category->image) : asset('assets/admin/img/noimage.jpg') }}"
                                                            alt="..." class="img-thumbnail" style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(0,0,0,0.08); padding: 2px;">
                                                    </td>
                                                    @if (in_array($userBs->theme, $allow_featured_theme))
                                                        <td class="align-middle" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04);">
                                                            <form action="{{ route('user.itemcategory.feature') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                                                <select class="form-control form-control-sm font-weight-bold {{ $category->is_feature == 1 ? 'text-success' : 'text-danger' }}" style="border-radius: 20px; width: auto; height: 28px; padding: 0 24px 0 12px; font-size: 12px; border: 1px solid {{ $category->is_feature == 1 ? '#28a745' : '#dc3545' }}; background-color: {{ $category->is_feature == 1 ? 'rgba(40, 167, 69, 0.08)' : 'rgba(220, 53, 69, 0.08)' }};" name="is_feature" onchange="this.closest('form').submit()">
                                                                    <option value="1" class="text-dark" {{ $category->is_feature == 1 ? 'selected' : '' }}>{{ __('Yes') }}</option>
                                                                    <option value="0" class="text-dark" {{ $category->is_feature == 0 ? 'selected' : '' }}>{{ __('No') }}</option>
                                                                </select>
                                                            </form>
                                                        </td>
                                                    @endif
                                                    <td class="align-middle" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04);">
                                                        @if ($category->status == 1)
                                                            <span class="badge badge-success font-weight-bold" style="border-radius: 20px; padding: 6px 14px; font-size: 12px; background: #28a745;">{{ __('Active') }}</span>
                                                        @else
                                                            <span class="badge badge-danger font-weight-bold" style="border-radius: 20px; padding: 6px 14px; font-size: 12px; background: #dc3545;">{{ __('Deactive') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle font-weight-bold text-muted" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04); font-size: 14px;">
                                                        {{ $category->serial_number }}
                                                    </td>
                                                    <td class="align-middle" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04); border-right: 1px solid rgba(0,0,0,0.04); border-radius: 0 8px 8px 0;">
                                                        <div class="d-flex align-items-center gap-1">
                                                            <a class="btn btn-primary btn-sm editbtn mr-1 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px; padding: 0; background: #0d6efd; border-color: #0d6efd;"
                                                                href="{{ route('user.itemcategory.edit', $category->id) . '?language=' . request()->input('language') }}" data-toggle="tooltip" title="{{ __('Edit') }}">
                                                                <i class="fas fa-pen" style="font-size: 12px; color: #fff;"></i>
                                                            </a>
                                                            <form class="deleteform d-inline-block mr-1" action="{{ route('user.itemcategory.delete') }}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                                                <button type="submit" class="btn btn-danger btn-sm deletebtn d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px; padding: 0; background: #dc3545; border-color: #dc3545;" data-toggle="tooltip" title="{{ __('Delete') }}">
                                                                    <i class="fas fa-trash" style="font-size: 12px; color: #fff;"></i>
                                                                </button>
                                                            </form>
                                                            <div class="dropdown d-inline-block">
                                                                <button class="btn btn-light btn-sm d-inline-flex align-items-center justify-content-center text-muted" type="button" style="width: 32px; height: 32px; border-radius: 8px; padding: 0; border: 1px solid rgba(0,0,0,0.08); background: transparent;" data-toggle="dropdown">
                                                                    <i class="fas fa-ellipsis-v" style="font-size: 12px;"></i>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <a class="dropdown-item" href="{{ route('user.itemcategory.edit', $category->id) . '?language=' . request()->input('language') }}">{{ __('Edit') }}</a>
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
                        @if (count($itemcategories) > 0)
                            {{ __('Showing') }} {{ $itemcategories->firstItem() }} {{ __('to') }} {{ $itemcategories->lastItem() }} {{ __('of') }} {{ $itemcategories->total() }} {{ __('entries') }}
                        @endif
                    </div>
                    <div>
                        {{ $itemcategories->appends(['language' => request()->input('language')])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Create Service Category Modal -->
    <div class="modal modal-v2 fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fw-bold" id="exampleModalLongTitle">{{ __('Add Category') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form id="ajaxForm" class="modal-form" enctype="multipart/form-data"
                        action="{{ route('user.itemcategory.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="ai_generated_image" id="ai_generated_image">
                        @if (in_array($userBs->theme, ['pet']))
                            <input type="hidden" name="ai_generated_bg_image" id="ai_generated_bg_image">
                        @endif

                        <div class="row">
                            @php
                                $allow_background_image_theme = ['pet'];
                            @endphp
                            @if (in_array($userBs->theme, $allow_background_image_theme))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-12 mb-2 pl-0">
                                            <label for="image"><strong>{{ __('Category Background Image') }} <span
                                                        class="text-danger">**</span></strong></label>
                                        </div>
                                        <div class="col-md-12 showImage mb-3 pl-0 pr-0">
                                            <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..."
                                                class="img-thumbnail bg-preview-img">
                                        </div>


                                        <div class="d-inline-flex align-items-center flex-wrap">
                                            <!-- Choose Image -->
                                            <div role="button" class="btn btn-primary btn-sm upload-btn mr-2"
                                                id="image2">
                                                {{ __('Choose Image') }}
                                                <input type="file" class="img-input" name="image">
                                            </div>

                                            <!-- AI Generate Image -->
                                            <button type="button" class="btn btn-info btn-sm" data-ai-image-open
                                                data-endpoint="{{ route('user.ai.generate.category.image') }}"
                                                data-target=".showImage .bg-preview-img"
                                                data-hidden="#ai_generated_bg_image"
                                                data-confirm-text="{{ __('Generate Background Image') }}">
                                                <i class="fas fa-magic mr-1"></i> {{ __('Generate Image') }}
                                            </button>
                                        </div>

                                        @if ($userBs->theme == 'pet')
                                            <p class="text-warning p-0 mb-1">
                                                {{ __('Recommended Image size : 210x210') }}
                                            </p>
                                        @else
                                            <p class="text-warning p-0 mb-1">
                                                {{ __('Recommended Image size : 100x100') }}
                                            </p>
                                        @endif

                                        <p id="errimage" class="mb-0 text-danger em"></p>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="col-12 mb-2 pl-0">
                                        <label for="image"><strong>{{ __('Category Image') }} <span
                                                    class="text-danger">**</span></strong></label>
                                    </div>
                                    <div class="col-md-12 showImage2 mb-3 pl-0 pr-0">
                                        <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..."
                                            class="img-thumbnail main-preview-img">
                                    </div>

                                    <div class="d-inline-flex align-items-center flex-wrap">
                                        <!-- Choose Image -->
                                        <div role="button" class="btn btn-primary btn-sm upload-btn mr-2"
                                            id="image2">
                                            {{ __('Choose Image') }}
                                            <input type="file" class="img-input" name="image">
                                        </div>

                                        <!-- AI Generate Image -->

                                        <button type="button" class="btn btn-info btn-sm" data-ai-image-open
                                            data-endpoint="{{ route('user.ai.generate.category.image') }}"
                                            data-target=".showImage2 .main-preview-img" data-hidden="#ai_generated_image"
                                            data-confirm-text="{{ __('Generate Image') }}">
                                            <i class="fas fa-magic mr-1"></i> {{ __('Generate Image') }}
                                        </button>
                                    </div>

                                    @if ($userBs->theme == 'pet')
                                        <p class="text-warning p-0 mb-1">
                                            {{ __('Recommended Image size') . ' : ' . __('210x210') }}
                                        </p>
                                    @elseif ($userBs->theme == 'jewellery')
                                        <p class="text-warning p-0 mb-1">
                                            {{ __('Recommended Image size') . ' : ' . __('220x340') }}
                                        </p>
                                    @else
                                        <p class="text-warning p-0 mb-1">
                                            {{ __('Recommended Image size') . ' : ' . __('100X100') }}
                                        </p>
                                    @endif
                                    <p id="errimage" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                        </div>



                        @foreach ($userLanguages as $lang)
                            <div class="form-group">
                                <label for="">{{ __('Name') }} ({{ $lang->name }})
                                    @if ($lang->is_default == 1)
                                        <span class="text-danger">**</span>
                                    @endif
                                </label>
                                <input type="text"
                                    class="form-control {{ $lang->rtl == 1 ? 'important_rtl text-right' : 'important_ltr' }}"
                                    name="{{ $lang->code }}_name" value=""
                                    placeholder="{{ __('Enter name') }}">
                                <p id="err{{ $lang->code }}_name" class="mb-0 text-danger em"></p>
                            </div>
                        @endforeach
                        @if ($userBs->theme == 'vegetables' || $userBs->theme == 'furniture')
                            <div class="form-group">
                                <label for="">{{ __('Color') }} <span class="text-danger">**</span></label>
                                <input type="text" class="form-control jscolor" name="color" value="">
                                <p id="errcolor" class="mb-0 text-danger em"></p>
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="">{{ __('Status') }} <span class="text-danger">**</span></label>
                            <select class="form-control" name="status">
                                <option value="" selected disabled>{{ __('Select Status') }}
                                </option>
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Deactive') }}</option>
                            </select>
                            <p id="errstatus" class="mb-0 text-danger em"></p>
                        </div>

                        <div class="form-group">
                            <label for="">{{ __('Serial Number') }} <span class="text-danger">**</span></label>
                            <input type="number" class="form-control" name="serial_number"
                                placeholder="{{ __('Enter Serial Number') }}">
                            <p id="errserial_number" class="mb-0 text-danger em"></p>
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

    {{-- ===================== AI CATEGORY NAME MODAL ===================== --}}
    <div class="modal modal-v2 fade" id="aiCategoryNameModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header border-0">
                    <h5 class="modal-title">{{ __('Generate Category Name') }}</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('Describe Your Category') }} <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="ai_cat_name_prompt" rows="3"
                            placeholder="e.g. Organic groceries, Premium skincare, Smart home gadgets..."></textarea>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary" id="aiGenerateCategoryNameConfirmBtn">
                        <i class="fas fa-magic mr-1"></i> {{ __('Generate') }}
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection
@section('scripts')
    {{-- include reusable js --}}
    <script src="{{ asset('assets/user/js/ai-image-modal.js') }}"></script>
    <script src="{{ asset('assets/user/js/ai-form-generator.js') }}"></script>
    <script>
        "use strict";

        AiFormGenerator.init({
            openBtn: '#aiGenerateCategoryNameBtn',
            modalId: '#aiCategoryNameModal',

            confirmBtn: '#aiGenerateCategoryNameConfirmBtn',
            endpoint: "{{ route('user.ai.generate.content') }}",

            prompt: {
                from: '#ai_cat_name_prompt'
            },

            outputs: {},
            inputSuffix: '_name',

            extra: {
                mode: 'category'
            }
        });
    </script>
@endsection

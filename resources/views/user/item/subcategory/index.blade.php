@extends('user.layout')

@php
    $userDefaultLang = \App\Models\User\Language::where([
        ['user_id', \Illuminate\Support\Facades\Auth::id()],
        ['is_default', 1],
    ])->first();
    $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
@endphp
@includeIf('user.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Subcategories') }}</h4>
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
                <a href="#">{{ __('Subcategories') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-header border-0 pb-0 pt-4 px-4 bg-transparent d-flex justify-content-between align-items-center flex-wrap">
                    <div class="d-flex align-items-center mb-2 mb-lg-0">
                        <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 44px; height: 44px; border-radius: 12px; background: rgba(13, 110, 253, 0.1);">
                            <i class="fas fa-layer-group text-primary" style="font-size: 20px;"></i>
                        </span>
                        <div>
                            <h4 class="card-title font-weight-bold mb-1" style="font-size: 18px;">{{ __('Subcategories') }}</h4>
                            <p class="text-muted mb-0" style="font-size: 13px;">{{ __('Manage your product subcategories') }}</p>
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
                            <i class="fas fa-plus mr-2"></i> {{ __('Add Subcategory') }}
                        </a>
                        <button class="btn btn-danger btn-sm font-weight-bold mr-2 d-none bulk-delete" style="border-radius: 8px; height: 36px;" data-href="{{ route('user.itemsubcategory.bulk.delete') }}">
                            <i class="flaticon-interface-5 mr-1"></i> {{ __('Delete') }}
                        </button>
                    </div>
                </div>
                @php
                    $icons = [
                        ['bg' => 'rgba(13, 110, 253, 0.1)', 'color' => '#0d6efd', 'icon' => 'fas fa-tag'],
                        ['bg' => 'rgba(40, 167, 69, 0.1)', 'color' => '#28a745', 'icon' => 'fas fa-cube'],
                        ['bg' => 'rgba(253, 126, 20, 0.1)', 'color' => '#fd7e14', 'icon' => 'fas fa-shopping-bag'],
                        ['bg' => 'rgba(111, 66, 193, 0.1)', 'color' => '#6f42c1', 'icon' => 'fas fa-puzzle-piece'],
                        ['bg' => 'rgba(232, 62, 140, 0.1)', 'color' => '#e83e8c', 'icon' => 'fas fa-gift'],
                        ['bg' => 'rgba(23, 162, 184, 0.1)', 'color' => '#17a2b8', 'icon' => 'fas fa-shapes'],
                    ];
                @endphp
                <div class="card-body px-4 pt-3 pb-4">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($itemsubcategories) == 0)
                                <h3 class="text-center py-5">{{ __('NO ITEM SUBCATEGORY FOUND') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover mt-3" style="border-collapse: separate; border-spacing: 0 8px;">
                                        <thead>
                                            <tr style="background: rgba(0,0,0,0.02);">
                                                <th scope="col" class="border-top-0 border-bottom-0" style="border-radius: 8px 0 0 8px; width: 40px;">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col" class="border-top-0 border-bottom-0 font-weight-bold">{{ __('Name') }}</th>
                                                <th scope="col" class="border-top-0 border-bottom-0 font-weight-bold">{{ __('Category') }}</th>
                                                <th scope="col" class="border-top-0 border-bottom-0 font-weight-bold">{{ __('Status') }}</th>
                                                <th scope="col" class="border-top-0 border-bottom-0 font-weight-bold" style="border-radius: 0 8px 8px 0;">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($itemsubcategories as $key => $category)
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
                                                    <td class="align-middle font-weight-bold" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04);">
                                                        <span class="badge font-weight-bold" style="background: rgba(13, 110, 253, 0.08); color: #0d6efd; border-radius: 20px; padding: 6px 14px; font-size: 12px; border: 1px solid rgba(13, 110, 253, 0.2);">
                                                            <i class="fas fa-folder mr-1"></i> {{ convertUtf8($category->category->name) }}
                                                        </span>
                                                    </td>
                                                    <td class="align-middle" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04);">
                                                        @if ($category->status == 1)
                                                            <span class="badge badge-success font-weight-bold" style="border-radius: 20px; padding: 6px 14px; font-size: 12px; background: #28a745;">{{ __('Active') }}</span>
                                                        @else
                                                            <span class="badge badge-danger font-weight-bold" style="border-radius: 20px; padding: 6px 14px; font-size: 12px; background: #dc3545;">{{ __('Deactive') }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="align-middle" style="border-top: 1px solid rgba(0,0,0,0.04); border-bottom: 1px solid rgba(0,0,0,0.04); border-right: 1px solid rgba(0,0,0,0.04); border-radius: 0 8px 8px 0;">
                                                        <div class="d-flex align-items-center gap-1">
                                                            <a class="btn btn-primary btn-sm editbtn mr-1 d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px; padding: 0; background: #0d6efd; border-color: #0d6efd;"
                                                                href="{{ route('user.itemsubcategory.edit', $category->id) . '?language=' . request()->input('language') }}" data-toggle="tooltip" title="{{ __('Edit') }}">
                                                                <i class="fas fa-pen" style="font-size: 12px; color: #fff;"></i>
                                                            </a>
                                                            <form class="deleteform d-inline-block mr-1" action="{{ route('user.itemsubcategory.delete') }}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="subcategory_id" value="{{ $category->id }}">
                                                                <button type="submit" class="btn btn-danger btn-sm deletebtn d-inline-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px; padding: 0; background: #dc3545; border-color: #dc3545;" data-toggle="tooltip" title="{{ __('Delete') }}">
                                                                    <i class="fas fa-trash" style="font-size: 12px; color: #fff;"></i>
                                                                </button>
                                                            </form>
                                                            <div class="dropdown d-inline-block">
                                                                <button class="btn btn-light btn-sm d-inline-flex align-items-center justify-content-center text-muted" type="button" style="width: 32px; height: 32px; border-radius: 8px; padding: 0; border: 1px solid rgba(0,0,0,0.08); background: transparent;" data-toggle="dropdown">
                                                                    <i class="fas fa-ellipsis-v" style="font-size: 12px;"></i>
                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    <a class="dropdown-item" href="{{ route('user.itemsubcategory.edit', $category->id) . '?language=' . request()->input('language') }}">{{ __('Edit') }}</a>
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
                        @if (count($itemsubcategories) > 0)
                            {{ __('Showing') }} {{ $itemsubcategories->firstItem() }} {{ __('to') }} {{ $itemsubcategories->lastItem() }} {{ __('of') }} {{ $itemsubcategories->total() }} {{ __('entries') }}
                        @endif
                    </div>
                    <div>
                        {{ $itemsubcategories->appends(['language' => request()->input('language')])->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Create Service Category Modal -->
    <div class="modal modal-v2 fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Subcategory') }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="ajaxForm" class="modal-form" enctype="multipart/form-data"
                        action="{{ route('user.itemsubcategory.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="language_code" value="{{ request()->input('language') }}">
                        <div class="form-group">
                            <label for="">{{ __('Category') }} <span class="text-danger">**</span></label>
                            <select id="language" name="category_id" class="form-control item_category">
                                <option value="" selected disabled>{{ __('Select Category') }}
                                </option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <p id="errcategory_id" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <button type="button" class="btn btn-info btn-sm" id="aiGenerateSubcategoryNameBtn">
                                <i class="fas fa-magic mr-1"></i> {{ __('Generate Subcategory Name') }}
                            </button>
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
                                    name="{{ $lang->code }}_name" placeholder="{{ __('Enter name') }}">
                                <p id="err{{ $lang->code }}_name" class="mb-0 text-danger em"></p>
                            </div>
                        @endforeach

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
    {{-- ===================== AI SUBCATEGORY NAME MODAL ===================== --}}
    <div class="modal modal-v2 fade" id="aiSubcategoryNameModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header border-0">
                    <h5 class="modal-title">{{ __('Generate Subcategory Name') }}</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('Related Category') }}</label>
                        <select class="form-control" id="ai_subcat_category_select">
                            <option value="" selected disabled>{{ __('Select Category') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-light d-block mt-2">
                            {{ __('Choose parent category to generate better subcategory name') . '.' }}
                        </small>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">{{ __('Describe Your Subcategory') }} <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" id="ai_subcat_name_prompt" rows="3"
                            placeholder="e.g. Gaming accessories, Organic fruits, Baby skincare..."></textarea>
                    </div>

                    <div id="aiSubcatNameErr" class="text-danger d-none"></div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary" id="aiGenerateSubcategoryNameConfirmBtn">
                        <i class="fas fa-magic mr-1"></i> {{ __('Generate') }}
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ asset('assets/user/js/ai-form-generator.js') }}"></script>

    <script>
        AiFormGenerator.init({
            openBtn: '#aiGenerateSubcategoryNameBtn',
            modalId: '#aiSubcategoryNameModal',

            confirmBtn: '#aiGenerateSubcategoryNameConfirmBtn',
            endpoint: "{{ route('user.ai.generate.content') }}",

            prompt: {
                from: '#ai_subcat_name_prompt'
            },

            engine: '#ai_subcat_name_engine',

            context: {
                category_id: '.item_category',
                category_name: () => $('.item_category option:selected').text()
            },

            outputs: {},
            inputSuffix: '_name',

            extra: {
                mode: 'subcategory'
            }
        });
    </script>
@endsection

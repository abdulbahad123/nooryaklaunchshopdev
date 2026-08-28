@extends('admin.layout')

@section('content')
    <div class="page-header d-flex align-items-center justify-content-between">
        <div>
            <h4 class="page-title d-flex align-items-center gap-2">
                {{ __('Package Features') }}
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
                    <a href="#">{{ __('Package Features') }}</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0" style="border-radius: 20px; box-shadow: var(--shadow-card);">
                <div class="card-header border-0 pb-0 pt-4 px-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="card-title m-0 font-weight-bold" style="font-size: 1.25rem;">{{ __('Package Features') }}</h3>
                    </div>
                    <div>
                        <button class="btn-primary-purple m-0 py-2 px-3" style="font-size: 0.85rem; border-radius: 10px;" data-toggle="modal" data-target="#addFeatureModal">
                            <i class="fas fa-plus mr-1"></i> {{ __('Add Feature') }}
                        </button>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Info Alert Box (Matching Screenshot 3) -->
                    <div class="d-flex align-items-start gap-3 mb-4" style="background: rgba(99, 102, 241, 0.06); border-left: 4px solid #6366F1; border-radius: 12px; padding: 16px 20px;">
                        <span class="cat-icon-badge i-purple m-0" style="width: 32px; height: 32px; font-size: 0.9rem; flex-shrink: 0; background: #EEF2FF; color: #6366F1;">
                            <i class="fas fa-info-circle"></i>
                        </span>
                        <p class="m-0 text-muted" style="font-size: 0.825rem; line-height: 1.5;">
                            {{ __('Configure features here. Standard features correspond to backend logic (e.g. Subdomain, Custom Domain, etc.). Limit features map to package_limit, and limit limits (like max products) set custom limits. Check frontend pricing table.') }}
                        </p>
                    </div>

                    <div class="table-responsive" style="overflow-x: auto; width: 100%;">
                        <table class="table table-hover align-middle" style="white-space: nowrap !important;">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 70px;">{{ __('Order') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col" style="width: 110px;">{{ __('Type') }}</th>
                                    <th scope="col">{{ __('Keyword/Key') }}</th>
                                    <th scope="col">{{ __('Limit Column') }}</th>
                                    <th scope="col" class="text-right" style="width: 100px;">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="features-sortable">
                                @if(count($features) == 0)
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">{{ __('No features found') }}</td>
                                    </tr>
                                @else
                                    @foreach($features as $feature)
                                        @if($feature->keyword === 'AI Content & Image Generator' || $feature->name === 'AI Content & Image Generator' || in_array($feature->name, ['Disqus', 'Bank Transfer Integrations']) || in_array($feature->keyword, ['Disqus', 'Bank Transfer Integrations']))
                                            @continue
                                        @endif
                                        <tr data-id="{{ $feature->id }}">
                                            <td>
                                                <span style="width: 26px; height: 26px; border-radius: 50%; background: #6366F1; color: #ffffff; font-weight: 700; font-size: 0.75rem; display: inline-flex; align-items: center; justify-content: center;">
                                                    {{ $feature->serial_number }}
                                                </span>
                                            </td>
                                            <td class="font-weight-bold text-dark" style="font-size: 0.875rem;">
                                                {{ $feature->name }}
                                            </td>
                                            <td>
                                                @if(strtolower($feature->type) === 'custom')
                                                    <span class="status-pill-active py-1 px-3" style="background: #F3E8FF !important; color: #7C3AED !important; font-size: 0.75rem;">
                                                        Custom
                                                    </span>
                                                @elseif(strtolower($feature->type) === 'standard')
                                                    <span class="status-pill-active py-1 px-3" style="background: #E0F2FE !important; color: #0284C7 !important; font-size: 0.75rem;">
                                                        Standard
                                                    </span>
                                                @else
                                                    <span class="status-pill-warning py-1 px-3" style="background: #FFEDD5 !important; color: #EA580C !important; font-size: 0.75rem;">
                                                        Limit
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <code style="color: #EC4899; font-weight: 600; font-size: 0.85rem; font-family: monospace; background: transparent; padding: 0;">
                                                    {{ $feature->keyword ?: '-' }}
                                                </code>
                                            </td>
                                            <td>
                                                <code style="color: #EC4899; font-weight: 600; font-size: 0.85rem; font-family: monospace; background: transparent; padding: 0;">
                                                    {{ $feature->limit_key ?: '-' }}
                                                </code>
                                            </td>
                                            <td class="text-right">
                                                <div class="d-inline-flex align-items-center gap-2">
                                                    <button class="btn-action-square b-edit edit-feature-btn" 
                                                            data-id="{{ $feature->id }}"
                                                            data-name="{{ $feature->name }}"
                                                            data-type="{{ $feature->type }}"
                                                            data-keyword="{{ $feature->keyword }}"
                                                            data-limit_key="{{ $feature->limit_key }}"
                                                            data-serial_number="{{ $feature->serial_number }}"
                                                            data-toggle="modal" 
                                                            data-target="#editFeatureModal"
                                                            title="{{ __('Edit') }}">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>
                                                    @if($feature->type === 'custom')
                                                        <form class="deleteform d-inline-block m-0" action="{{ route('admin.package.features_delete') }}" method="post">
                                                            @csrf
                                                            <input type="hidden" name="feature_id" value="{{ $feature->id }}">
                                                            <button type="submit" class="btn-action-square b-delete deletebtn" title="{{ __('Delete') }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Feature Modal -->
    <div class="modal fade" id="addFeatureModal" tabindex="-1" role="dialog" aria-labelledby="addFeatureModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add New Feature') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.package.features_store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="add_name">{{ __('Feature Name') }} <span class="text-danger">**</span></label>
                            <input id="add_name" type="text" class="form-control" name="name" placeholder="e.g. {limit} Products limit or Wishlist Features" required>
                            <small class="form-text text-muted">Use <code>{limit}</code> as placeholder for limit values.</small>
                        </div>
                        <div class="form-group">
                            <label for="add_type">{{ __('Feature Type') }} <span class="text-danger">**</span></label>
                            <select id="add_type" name="type" class="form-control" required>
                                <option value="standard">{{ __('Standard/Checkbox (Logic-bound)') }}</option>
                                <option value="limit">{{ __('Limit-bound (Numeric limit)') }}</option>
                                <option value="custom">{{ __('Custom/Checkbox (Display-only)') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="add_keyword">{{ __('Keyword/Key') }}</label>
                            <input id="add_keyword" type="text" class="form-control" name="keyword" placeholder="e.g. Subdomain, Custom Domain, or custom identifier">
                            <small class="form-text text-muted">Maps to system logic checks.</small>
                        </div>
                        <div class="form-group">
                            <label for="add_limit_key">{{ __('Limit Column Mapping') }}</label>
                            <select id="add_limit_key" name="limit_key" class="form-control">
                                <option value="">{{ __('None') }}</option>
                                <option value="product_limit">{{ __('product_limit') }}</option>
                                <option value="categories_limit">{{ __('categories_limit') }}</option>
                                <option value="subcategories_limit">{{ __('subcategories_limit') }}</option>
                                <option value="order_limit">{{ __('order_limit') }}</option>
                                <option value="language_limit">{{ __('language_limit') }}</option>
                                <option value="post_limit">{{ __('post_limit') }}</option>
                                <option value="number_of_custom_page">{{ __('number_of_custom_page') }}</option>
                                <option value="ai_token_limit">{{ __('ai_token_limit') }}</option>
                                <option value="ai_image_limit">{{ __('ai_image_limit') }}</option>
                            </select>
                            <small class="form-text text-muted">Select if Feature Type is Limit-bound.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Feature Modal -->
    <div class="modal fade" id="editFeatureModal" tabindex="-1" role="dialog" aria-labelledby="editFeatureModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Edit Feature') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.package.features_update_single') }}" method="POST">
                    @csrf
                    <input type="hidden" id="edit_feature_id" name="feature_id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name">{{ __('Feature Name') }} <span class="text-danger">**</span></label>
                            <input id="edit_name" type="text" class="form-control" name="name" placeholder="e.g. {limit} Products limit" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_type">{{ __('Feature Type') }} <span class="text-danger">**</span></label>
                            <select id="edit_type" name="type" class="form-control" required>
                                <option value="standard">{{ __('Standard/Checkbox (Logic-bound)') }}</option>
                                <option value="limit">{{ __('Limit-bound (Numeric limit)') }}</option>
                                <option value="custom">{{ __('Custom/Checkbox (Display-only)') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_keyword">{{ __('Keyword/Key') }}</label>
                            <input id="edit_keyword" type="text" class="form-control" name="keyword">
                        </div>
                        <div class="form-group">
                            <label for="edit_limit_key">{{ __('Limit Column Mapping') }}</label>
                            <select id="edit_limit_key" name="limit_key" class="form-control">
                                <option value="">{{ __('None') }}</option>
                                <option value="product_limit">{{ __('product_limit') }}</option>
                                <option value="categories_limit">{{ __('categories_limit') }}</option>
                                <option value="subcategories_limit">{{ __('subcategories_limit') }}</option>
                                <option value="order_limit">{{ __('order_limit') }}</option>
                                <option value="language_limit">{{ __('language_limit') }}</option>
                                <option value="post_limit">{{ __('post_limit') }}</option>
                                <option value="number_of_custom_page">{{ __('number_of_custom_page') }}</option>
                                <option value="ai_token_limit">{{ __('ai_token_limit') }}</option>
                                <option value="ai_image_limit">{{ __('ai_image_limit') }}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_serial_number">{{ __('Order / Serial Number') }} <span class="text-danger">**</span></label>
                            <input id="edit_serial_number" type="number" class="form-control" name="serial_number" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.edit-feature-btn').on('click', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var type = $(this).data('type');
                var keyword = $(this).data('keyword');
                var limit_key = $(this).data('limit_key');
                var serial_number = $(this).data('serial_number');

                $('#edit_feature_id').val(id);
                $('#edit_name').val(name);
                $('#edit_type').val(type);
                $('#edit_keyword').val(keyword);
                $('#edit_limit_key').val(limit_key || '');
                $('#edit_serial_number').val(serial_number);
            });
        });
    </script>
@endsection

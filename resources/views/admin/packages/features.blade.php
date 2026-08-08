@extends('admin.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Package Features') }}</h4>
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
                <a href="#">{{ __('Packages Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Package Features') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card-title d-inline-block">{{ __('Package Features') }}</div>
                        </div>
                        <div class="col-lg-4 mt-2 mt-lg-0 text-right">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addFeatureModal">
                                <i class="fas fa-plus"></i> {{ __('Add Feature') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning text-dark">
                        {{ __('Configure features here. standard features correspond to backend logic (e.g. Subdomain, Custom Domain, etc.), limit features map to package columns (e.g. product_limit), and custom features are checkbox items with no backend logic. Order determines display on frontend Pricing Table.') }}
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped mt-3">
                            <thead>
                                <tr>
                                    <th scope="col">{{ __('Order') }}</th>
                                    <th scope="col">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Type') }}</th>
                                    <th scope="col">{{ __('Keyword/Key') }}</th>
                                    <th scope="col">{{ __('Limit Column') }}</th>
                                    <th scope="col">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody id="features-sortable">
                                @if(count($features) == 0)
                                    <tr>
                                        <td colspan="6" class="text-center">{{ __('No features found') }}</td>
                                    </tr>
                                @else
                                    @foreach($features as $feature)
                                        @if($feature->keyword === 'AI Content & Image Generator' || $feature->name === 'AI Content & Image Generator' || in_array($feature->name, ['Disqus', 'Bank Transfer Integrations', 'Facebook Pixel']) || in_array($feature->keyword, ['Disqus', 'Bank Transfer Integrations', 'Facebook Pixel']))
                                            @continue
                                        @endif
                                        <tr data-id="{{ $feature->id }}">
                                            <td>
                                                <span class="badge badge-secondary">{{ $feature->serial_number }}</span>
                                            </td>
                                            <td><strong>{{ $feature->name }}</strong></td>
                                            <td>
                                                <span class="badge badge-info">{{ ucfirst($feature->type) }}</span>
                                            </td>
                                            <td><code>{{ $feature->keyword ?: '-' }}</code></td>
                                            <td><code>{{ $feature->limit_key ?: '-' }}</code></td>
                                            <td>
                                                <button class="btn btn-secondary btn-sm edit-feature-btn" 
                                                        data-id="{{ $feature->id }}"
                                                        data-name="{{ $feature->name }}"
                                                        data-type="{{ $feature->type }}"
                                                        data-keyword="{{ $feature->keyword }}"
                                                        data-limit_key="{{ $feature->limit_key }}"
                                                        data-serial_number="{{ $feature->serial_number }}"
                                                        data-toggle="modal" 
                                                        data-target="#editFeatureModal">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                @if($feature->type === 'custom')
                                                    <form class="deleteform d-inline-block" action="{{ route('admin.package.features_delete') }}" method="post">
                                                        @csrf
                                                        <input type="hidden" name="feature_id" value="{{ $feature->id }}">
                                                        <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
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

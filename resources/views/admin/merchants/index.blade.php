@extends('admin.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Stores Launched / Merchants') }}</h4>
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
        <a href="#">{{ __('Stores Launched') }}</a>
      </li>
    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card" style="border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 4px 14px rgba(0,0,0,0.03);">
        <div class="card-header bg-white py-3">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <h4 class="card-title font-weight-bold mb-0 text-dark">
                <i class="fas fa-store-alt mr-2 text-primary"></i> {{ __('Stores Launched Page Manager') }}
              </h4>
              <p class="text-muted small mb-0 mt-1">{{ __('Manage Featured Merchants, Reviews, Why Choose Cards, and Section Headlines on the /shops page.') }}</p>
            </div>
            <div class="col-lg-6 text-right">
              @include('admin.partials.languages')
            </div>
          </div>
        </div>

        <div class="card-body p-4">
          <!-- Nav Tabs -->
          <ul class="nav nav-pills nav-secondary mb-4" id="pills-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active font-weight-bold" id="tab-headers-link" data-toggle="pill" href="#tab-headers" role="tab">
                <i class="fas fa-heading mr-1"></i> {{ __('Section Headings') }}
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link font-weight-bold" id="tab-featured-link" data-toggle="pill" href="#tab-featured" role="tab">
                <i class="fas fa-user-tie mr-1"></i> {{ __('Featured Merchants') }} ({{ count($featuredMerchants) }})
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link font-weight-bold" id="tab-loved-link" data-toggle="pill" href="#tab-loved" role="tab">
                <i class="fas fa-heart mr-1"></i> {{ __('Loved by Merchants (Reviews)') }} ({{ count($lovedMerchants) }})
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link font-weight-bold" id="tab-why-link" data-toggle="pill" href="#tab-why" role="tab">
                <i class="fas fa-check-double mr-1"></i> {{ __('Why Choose Cards') }} ({{ count($whyChooseCards) }})
              </a>
            </li>
          </ul>

          <div class="tab-content" id="pills-tabContent">
            
            <!-- 1. SECTION HEADINGS TAB -->
            <div class="tab-pane fade show active" id="tab-headers" role="tabpanel">
              <div class="card border">
                <div class="card-header bg-light">
                  <h5 class="mb-0 font-weight-bold"><i class="fas fa-pen mr-2 text-primary"></i>{{ __('Section Titles & Subtitles') }}</h5>
                </div>
                <div class="card-body p-4">
                  <form action="{{ route('admin.merchants.update_headers', $lang_id) }}" method="POST">
                    @csrf
                    
                    <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-award mr-1"></i> 1. {{ __('Featured Merchants Section') }}</h6>
                    <div class="row mb-4">
                      <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">{{ __('Section Title') }}</label>
                        <input type="text" class="form-control" name="shops_featured_merchants_title" 
                               value="{{ @$homeSec->shops_featured_merchants_title ?: 'Featured Merchants' }}" 
                               placeholder="e.g. Featured Merchants">
                      </div>
                      <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">{{ __('Section Subtitle') }}</label>
                        <input type="text" class="form-control" name="shops_featured_merchants_subtitle" 
                               value="{{ @$homeSec->shops_featured_merchants_subtitle ?: 'Real stories from real entrepreneurs building successful brands.' }}" 
                               placeholder="e.g. Real stories from real entrepreneurs building successful brands.">
                      </div>
                    </div>

                    <hr>

                    <h6 class="font-weight-bold text-danger mb-3"><i class="fas fa-heart mr-1"></i> 2. {{ __('Loved by Merchants Section') }}</h6>
                    <div class="row mb-4">
                      <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">{{ __('Section Title') }}</label>
                        <input type="text" class="form-control" name="shops_loved_merchants_title" 
                               value="{{ @$homeSec->shops_loved_merchants_title ?: 'Loved by Merchants' }}" 
                               placeholder="e.g. Loved by Merchants">
                      </div>
                      <div class="col-md-6 mb-3">
                        <label class="font-weight-bold">{{ __('Section Subtitle') }}</label>
                        <input type="text" class="form-control" name="shops_loved_merchants_subtitle" 
                               value="{{ @$homeSec->shops_loved_merchants_subtitle ?: 'Hear from store owners who are growing their business with EcomBuilder.in.' }}" 
                               placeholder="e.g. Hear from store owners who are growing their business with EcomBuilder.in.">
                      </div>
                    </div>

                    <hr>

                    <h6 class="font-weight-bold text-success mb-3"><i class="fas fa-shield-alt mr-1"></i> 3. {{ __('Why Choose Section') }}</h6>
                    <div class="row mb-4">
                      <div class="col-md-12 mb-3">
                        <label class="font-weight-bold">{{ __('Section Title') }}</label>
                        <input type="text" class="form-control" name="shops_why_choose_title" 
                               value="{{ @$homeSec->shops_why_choose_title ?: 'Why Choose Stores Built on EcomBuilder.in?' }}" 
                               placeholder="e.g. Why Choose Stores Built on EcomBuilder.in?">
                      </div>
                    </div>

                    <div class="text-right">
                      <button type="submit" class="btn btn-primary font-weight-bold px-4">
                        <i class="fas fa-save mr-1"></i> {{ __('Save Section Headings') }}
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- 2. FEATURED MERCHANTS TAB -->
            <div class="tab-pane fade" id="tab-featured" role="tabpanel">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="font-weight-bold mb-0">{{ __('Featured Merchants Carousel Cards') }}</h5>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-toggle="modal" data-target="#addFeaturedModal">
                  <i class="fas fa-plus mr-1"></i> {{ __('Add Featured Merchant') }}
                </button>
              </div>

              <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                  <thead class="thead-light">
                    <tr>
                      <th>#</th>
                      <th>{{ __('Photo') }}</th>
                      <th>{{ __('Name') }}</th>
                      <th>{{ __('Role / Company') }}</th>
                      <th>{{ __('Quote / Story') }}</th>
                      <th>{{ __('Order') }}</th>
                      <th>{{ __('Actions') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($featuredMerchants as $m)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                          @php
                            $imgSrc = $m->image ? (file_exists(public_path('assets/front/img/merchants/' . $m->image)) ? asset('assets/front/img/merchants/' . $m->image) : asset('assets/front/img/testimonials/' . $m->image)) : asset('assets/front/img/testimonials/indian_man.png');
                          @endphp
                          <img src="{{ $imgSrc }}" alt="{{ $m->name }}" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0;">
                        </td>
                        <td class="font-weight-bold">{{ $m->name }}</td>
                        <td><span class="badge badge-info">{{ $m->designation }}</span></td>
                        <td style="max-width: 280px;" class="small text-muted">{{ $m->quote }}</td>
                        <td>{{ $m->serial_number }}</td>
                        <td>
                          <button class="btn btn-sm btn-secondary mr-1" data-toggle="modal" data-target="#editModal{{ $m->id }}">
                            <i class="fas fa-edit"></i>
                          </button>
                          <form action="{{ route('admin.merchants.delete_item', $m->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this merchant?') }}');">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">
                              <i class="fas fa-trash"></i>
                            </button>
                          </form>
                        </td>
                      </tr>

                      <!-- Edit Modal -->
                      <div class="modal fade" id="editModal{{ $m->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <form action="{{ route('admin.merchants.update_item', $m->id) }}" method="POST" enctype="multipart/form-data">
                              @csrf
                              <div class="modal-header">
                                <h5 class="modal-title font-weight-bold">{{ __('Edit Featured Merchant') }}</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                              <div class="modal-body">
                                <div class="form-group">
                                  <label class="font-weight-bold">{{ __('Merchant Name') }} *</label>
                                  <input type="text" class="form-control" name="name" value="{{ $m->name }}" required>
                                </div>
                                <div class="form-group">
                                  <label class="font-weight-bold">{{ __('Role / Store Name') }} *</label>
                                  <input type="text" class="form-control" name="designation" value="{{ $m->designation }}" placeholder="e.g. Founder, Electri" required>
                                </div>
                                <div class="form-group">
                                  <label class="font-weight-bold">{{ __('Quote / Success Story') }} *</label>
                                  <textarea class="form-control" name="quote" rows="3" required>{{ $m->quote }}</textarea>
                                </div>
                                <div class="form-group">
                                  <label class="font-weight-bold">{{ __('Update Photo (Optional)') }}</label>
                                  <input type="file" class="form-control-file" name="image" accept="image/*">
                                </div>
                                <div class="form-group">
                                  <label class="font-weight-bold">{{ __('Display Order') }}</label>
                                  <input type="number" class="form-control" name="serial_number" value="{{ $m->serial_number }}">
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                <button type="submit" class="btn btn-primary font-weight-bold">{{ __('Save Changes') }}</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center py-4 text-muted">{{ __('No featured merchants found.') }}</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

            <!-- 3. LOVED BY MERCHANTS TAB -->
            <div class="tab-pane fade" id="tab-loved" role="tabpanel">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="font-weight-bold mb-0">{{ __('Loved by Merchants (Reviews & Ratings)') }}</h5>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-toggle="modal" data-target="#addLovedModal">
                  <i class="fas fa-plus mr-1"></i> {{ __('Add Merchant Review') }}
                </button>
              </div>

              <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                  <thead class="thead-light">
                    <tr>
                      <th>#</th>
                      <th>{{ __('Rating') }}</th>
                      <th>{{ __('Author Name') }}</th>
                      <th>{{ __('Role / Store Name') }}</th>
                      <th>{{ __('Review Quote') }}</th>
                      <th>{{ __('Order') }}</th>
                      <th>{{ __('Actions') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($lovedMerchants as $rev)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                          <span class="text-warning">
                            @for($i=1; $i<=($rev->rating ?? 5); $i++)
                              <i class="fas fa-star"></i>
                            @endfor
                          </span>
                        </td>
                        <td class="font-weight-bold">{{ $rev->name }}</td>
                        <td><span class="badge badge-success">{{ $rev->designation }}</span></td>
                        <td style="max-width: 300px;" class="small text-muted">{{ $rev->quote }}</td>
                        <td>{{ $rev->serial_number }}</td>
                        <td>
                          <button class="btn btn-sm btn-secondary mr-1" data-toggle="modal" data-target="#editLovedModal{{ $rev->id }}">
                            <i class="fas fa-edit"></i>
                          </button>
                          <form action="{{ route('admin.merchants.delete_item', $rev->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this review?') }}');">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">
                              <i class="fas fa-trash"></i>
                            </button>
                          </form>
                        </td>
                      </tr>

                      <!-- Edit Loved Modal -->
                      <div class="modal fade" id="editLovedModal{{ $rev->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <form action="{{ route('admin.merchants.update_item', $rev->id) }}" method="POST">
                              @csrf
                              <div class="modal-header">
                                <h5 class="modal-title font-weight-bold">{{ __('Edit Merchant Review') }}</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                              <div class="modal-body">
                                <div class="form-group">
                                  <label class="font-weight-bold">{{ __('Author Name') }} *</label>
                                  <input type="text" class="form-control" name="name" value="{{ $rev->name }}" required>
                                </div>
                                <div class="form-group">
                                  <label class="font-weight-bold">{{ __('Role / Store Name') }} *</label>
                                  <input type="text" class="form-control" name="designation" value="{{ $rev->designation }}" placeholder="e.g. Founder, FreshMart" required>
                                </div>
                                <div class="form-group">
                                  <label class="font-weight-bold">{{ __('Star Rating') }}</label>
                                  <select class="form-control" name="rating">
                                    <option value="5" @if($rev->rating == 5) selected @endif>⭐⭐⭐⭐⭐ (5 Stars)</option>
                                    <option value="4" @if($rev->rating == 4) selected @endif>⭐⭐⭐⭐ (4 Stars)</option>
                                    <option value="3" @if($rev->rating == 3) selected @endif>⭐⭐⭐ (3 Stars)</option>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label class="font-weight-bold">{{ __('Review Text') }} *</label>
                                  <textarea class="form-control" name="quote" rows="3" required>{{ $rev->quote }}</textarea>
                                </div>
                                <div class="form-group">
                                  <label class="font-weight-bold">{{ __('Display Order') }}</label>
                                  <input type="number" class="form-control" name="serial_number" value="{{ $rev->serial_number }}">
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                <button type="submit" class="btn btn-primary font-weight-bold">{{ __('Save Changes') }}</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center py-4 text-muted">{{ __('No merchant reviews found.') }}</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

            <!-- 4. WHY CHOOSE CARDS TAB -->
            <div class="tab-pane fade" id="tab-why" role="tabpanel">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="font-weight-bold mb-0">{{ __('Why Choose Us Cards (5 Pillars)') }}</h5>
                <button type="button" class="btn btn-primary btn-sm font-weight-bold" data-toggle="modal" data-target="#addWhyModal">
                  <i class="fas fa-plus mr-1"></i> {{ __('Add Card') }}
                </button>
              </div>

              <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                  <thead class="thead-light">
                    <tr>
                      <th>#</th>
                      <th>{{ __('Icon') }}</th>
                      <th>{{ __('Title') }}</th>
                      <th>{{ __('Description') }}</th>
                      <th>{{ __('Color') }}</th>
                      <th>{{ __('Order') }}</th>
                      <th>{{ __('Actions') }}</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($whyChooseCards as $card)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                          <span class="badge badge-light p-2 font-size-16">
                            <i class="{{ $card->icon ?? 'fas fa-mobile-alt' }} text-primary"></i>
                          </span>
                        </td>
                        <td class="font-weight-bold">{{ $card->name }}</td>
                        <td style="max-width: 300px;" class="small text-muted">{{ $card->quote }}</td>
                        <td><span class="badge badge-secondary">{{ $card->badge_color ?? 'orange' }}</span></td>
                        <td>{{ $card->serial_number }}</td>
                        <td>
                          <button class="btn btn-sm btn-secondary mr-1" data-toggle="modal" data-target="#editWhyModal{{ $card->id }}">
                            <i class="fas fa-edit"></i>
                          </button>
                          <form action="{{ route('admin.merchants.delete_item', $card->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('{{ __('Are you sure you want to delete this card?') }}');">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger">
                              <i class="fas fa-trash"></i>
                            </button>
                          </form>
                        </td>
                      </tr>

                      <!-- Edit Why Modal -->
                      <div class="modal fade" id="editWhyModal{{ $card->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                          <div class="modal-content">
                            <form action="{{ route('admin.merchants.update_item', $card->id) }}" method="POST">
                              @csrf
                              <div class="modal-header">
                                <h5 class="modal-title font-weight-bold">{{ __('Edit Why Choose Card') }}</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                              </div>
                              <div class="modal-body">
                                <div class="form-group">
                                  <label class="font-weight-bold">{{ __('Card Title') }} *</label>
                                  <input type="text" class="form-control" name="name" value="{{ $card->name }}" required>
                                </div>
                                <div class="form-group">
                                  <label class="font-weight-bold">{{ __('FontAwesome Icon Class') }} *</label>
                                  <input type="text" class="form-control" name="icon" value="{{ $card->icon ?? 'fas fa-mobile-alt' }}" placeholder="e.g. fas fa-rocket" required>
                                </div>
                                <div class="form-group">
                                  <label class="font-weight-bold">{{ __('Icon Color Theme') }}</label>
                                  <select class="form-control" name="badge_color">
                                    <option value="orange" @if($card->badge_color == 'orange') selected @endif>Orange</option>
                                    <option value="red" @if($card->badge_color == 'red') selected @endif>Red</option>
                                    <option value="blue" @if($card->badge_color == 'blue') selected @endif>Blue</option>
                                    <option value="green" @if($card->badge_color == 'green') selected @endif>Green</option>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label class="font-weight-bold">{{ __('Description Text') }} *</label>
                                  <textarea class="form-control" name="quote" rows="3" required>{{ $card->quote }}</textarea>
                                </div>
                                <div class="form-group">
                                  <label class="font-weight-bold">{{ __('Display Order') }}</label>
                                  <input type="number" class="form-control" name="serial_number" value="{{ $card->serial_number }}">
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                                <button type="submit" class="btn btn-primary font-weight-bold">{{ __('Save Changes') }}</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center py-4 text-muted">{{ __('No cards found.') }}</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ADD FEATURED MODAL -->
  <div class="modal fade" id="addFeaturedModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form action="{{ route('admin.merchants.store_item') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="language_id" value="{{ $lang_id }}">
          <input type="hidden" name="type" value="featured">
          <div class="modal-header">
            <h5 class="modal-title font-weight-bold">{{ __('Add Featured Merchant') }}</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="font-weight-bold">{{ __('Merchant Name') }} *</label>
              <input type="text" class="form-control" name="name" placeholder="e.g. Ayaan Patel" required>
            </div>
            <div class="form-group">
              <label class="font-weight-bold">{{ __('Role / Store Name') }} *</label>
              <input type="text" class="form-control" name="designation" placeholder="e.g. Founder, Electri" required>
            </div>
            <div class="form-group">
              <label class="font-weight-bold">{{ __('Quote / Story') }} *</label>
              <textarea class="form-control" name="quote" rows="3" placeholder="e.g. Expanded to 5 cities with EcomBuilder.in." required></textarea>
            </div>
            <div class="form-group">
              <label class="font-weight-bold">{{ __('Merchant Photo') }}</label>
              <input type="file" class="form-control-file" name="image" accept="image/*">
            </div>
            <div class="form-group">
              <label class="font-weight-bold">{{ __('Display Order') }}</label>
              <input type="number" class="form-control" name="serial_number" value="10">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="btn btn-primary font-weight-bold">{{ __('Add Merchant') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ADD LOVED MODAL -->
  <div class="modal fade" id="addLovedModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form action="{{ route('admin.merchants.store_item') }}" method="POST">
          @csrf
          <input type="hidden" name="language_id" value="{{ $lang_id }}">
          <input type="hidden" name="type" value="review">
          <div class="modal-header">
            <h5 class="modal-title font-weight-bold">{{ __('Add Merchant Review') }}</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="font-weight-bold">{{ __('Author Name') }} *</label>
              <input type="text" class="form-control" name="name" placeholder="e.g. Priya Mehta" required>
            </div>
            <div class="form-group">
              <label class="font-weight-bold">{{ __('Role / Store Name') }} *</label>
              <input type="text" class="form-control" name="designation" placeholder="e.g. Founder, Skinflow" required>
            </div>
            <div class="form-group">
              <label class="font-weight-bold">{{ __('Star Rating') }}</label>
              <select class="form-control" name="rating">
                <option value="5" selected>⭐⭐⭐⭐⭐ (5 Stars)</option>
                <option value="4">⭐⭐⭐⭐ (4 Stars)</option>
                <option value="3">⭐⭐⭐ (3 Stars)</option>
              </select>
            </div>
            <div class="form-group">
              <label class="font-weight-bold">{{ __('Review Text') }} *</label>
              <textarea class="form-control" name="quote" rows="3" placeholder="e.g. I love how customizable everything is..." required></textarea>
            </div>
            <div class="form-group">
              <label class="font-weight-bold">{{ __('Display Order') }}</label>
              <input type="number" class="form-control" name="serial_number" value="10">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="btn btn-primary font-weight-bold">{{ __('Add Review') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- ADD WHY MODAL -->
  <div class="modal fade" id="addWhyModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form action="{{ route('admin.merchants.store_item') }}" method="POST">
          @csrf
          <input type="hidden" name="language_id" value="{{ $lang_id }}">
          <input type="hidden" name="type" value="why_choose">
          <div class="modal-header">
            <h5 class="modal-title font-weight-bold">{{ __('Add Why Choose Card') }}</h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label class="font-weight-bold">{{ __('Card Title') }} *</label>
              <input type="text" class="form-control" name="name" placeholder="e.g. Beautiful & Fast Themes" required>
            </div>
            <div class="form-group">
              <label class="font-weight-bold">{{ __('FontAwesome Icon Class') }} *</label>
              <input type="text" class="form-control" name="icon" value="fas fa-mobile-alt" placeholder="e.g. fas fa-mobile-alt" required>
            </div>
            <div class="form-group">
              <label class="font-weight-bold">{{ __('Icon Color Theme') }}</label>
              <select class="form-control" name="badge_color">
                <option value="orange" selected>Orange</option>
                <option value="red">Red</option>
                <option value="blue">Blue</option>
                <option value="green">Green</option>
              </select>
            </div>
            <div class="form-group">
              <label class="font-weight-bold">{{ __('Description Text') }} *</label>
              <textarea class="form-control" name="quote" rows="3" placeholder="e.g. Mobile-first, high-converting themes..." required></textarea>
            </div>
            <div class="form-group">
              <label class="font-weight-bold">{{ __('Display Order') }}</label>
              <input type="number" class="form-control" name="serial_number" value="10">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
            <button type="submit" class="btn btn-primary font-weight-bold">{{ __('Add Card') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection
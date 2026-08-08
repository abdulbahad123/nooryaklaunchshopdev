<!-- show limit check Modal -->
@if ($currPackage)
  <div class="modal fade" id="limitModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
        <div class="modal-header border-bottom py-4 px-4 bg-transparent d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center">
            <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 44px; height: 44px; border-radius: 50%; background: rgba(13, 110, 253, 0.1); flex-shrink: 0;">
              <i class="fas fa-shield-alt text-primary" style="font-size: 20px;"></i>
            </span>
            <div>
              <h4 class="modal-title font-weight-bold mb-1" id="exampleModalLabel" style="font-size: 18px; color: #212529;">{{ __('System Limits & Status') }}</h4>
              <p class="text-muted mb-0" style="font-size: 13px;">{{ __('View your current usage and system feature status.') }}</p>
            </div>
          </div>
          <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close" style="font-size: 24px; opacity: 0.6;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4" style="max-height: 65vh; overflow-y: auto;">
          <ul class="list-group list-group-flush limit-modal gap-2" style="display: flex; flex-direction: column; gap: 10px;">
            @php
              $aiTokenTotalLabel = $aiTokenTotalLimit < 999999 ? $aiTokenTotalLimit : __('Unlimited');
              $aiImageTotalLabel = $aiImageTotalLimit < 999999 ? $aiImageTotalLimit : __('Unlimited');
            @endphp

            @if(false)
            <!-- AI Engine -->
            <li class="list-group-item border-0 p-3 shadow-sm d-flex justify-content-between align-items-center" style="border-radius: 12px; background: #ffffff; border: 1px solid rgba(0,0,0,0.06) !important;">
              <div class="d-flex align-items-center">
                <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 38px; height: 38px; border-radius: 10px; background: rgba(13, 110, 253, 0.1); color: #0d6efd; flex-shrink: 0;">
                  <i class="fas fa-robot" style="font-size: 16px;"></i>
                </span>
                <span class="font-weight-600 ai-tooltip" tabindex="0" style="font-size: 14px; color: #333;">
                  {{ __('AI Engine') }} <i class="fas fa-info-circle text-muted ml-1" style="font-size: 12px; opacity: 0.7;"></i>
                  <span class="ai-tooltip-box" role="tooltip">
                    <ul class="ai-tooltip-list">
                      <li>{{ __('Total Tokens') }}: {{ $aiTokenTotalLabel }}</li>
                      <li>{{ __('Used Tokens') }}: {{ $aiUsedTokens }}</li>
                      <li>{{ __('Total Images') }}: {{ $aiImageTotalLabel }}</li>
                      <li>{{ __('Used Images') }}: {{ $aiUsedImages }}</li>
                    </ul>
                  </span>
                </span>
              </div>
              <span class="badge font-weight-bold" style="background: #0d6efd; color: #ffffff; border-radius: 20px; padding: 6px 16px; font-size: 12px;">
                {{ $aiEngine ? strtoupper($aiEngine) : __('N/A') }}
              </span>
            </li>

            <!-- AI Total Tokens -->
            <li class="list-group-item border-0 p-3 shadow-sm d-flex justify-content-between align-items-center" style="border-radius: 12px; background: #ffffff; border: 1px solid rgba(0,0,0,0.06) !important;">
              <div class="d-flex align-items-center">
                <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 38px; height: 38px; border-radius: 10px; background: rgba(111, 66, 193, 0.1); color: #6f42c1; flex-shrink: 0;">
                  <i class="fas fa-font" style="font-size: 16px;"></i>
                </span>
                <span class="font-weight-600 ai-tooltip" tabindex="0" style="font-size: 14px; color: #333;">
                  {{ __('AI Total Tokens') }} <i class="fas fa-info-circle text-muted ml-1" style="font-size: 12px; opacity: 0.7;"></i>
                  <span class="ai-tooltip-box" role="tooltip">
                    <ul class="ai-tooltip-list">
                      <li>{{ __('Total Tokens') }}: {{ $aiTokenTotalLabel }}</li>
                      <li>{{ __('Used Tokens') }}: {{ $aiUsedTokens }}</li>
                    </ul>
                  </span>
                </span>
              </div>
              <span class="badge font-weight-bold" style="background: #0d6efd; color: #ffffff; border-radius: 20px; padding: 6px 16px; font-size: 12px;">
                {{ $aiUsedTokens }} / {{ $aiTokenTotalLabel }}
              </span>
            </li>

            <!-- AI Generated Total Images -->
            <li class="list-group-item border-0 p-3 shadow-sm d-flex justify-content-between align-items-center" style="border-radius: 12px; background: #ffffff; border: 1px solid rgba(0,0,0,0.06) !important;">
              <div class="d-flex align-items-center">
                <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 38px; height: 38px; border-radius: 10px; background: rgba(40, 167, 69, 0.1); color: #28a745; flex-shrink: 0;">
                  <i class="fas fa-image" style="font-size: 16px;"></i>
                </span>
                <span class="font-weight-600 ai-tooltip" tabindex="0" style="font-size: 14px; color: #333;">
                  {{ __('AI Generated Total Images') }} <i class="fas fa-info-circle text-muted ml-1" style="font-size: 12px; opacity: 0.7;"></i>
                  <span class="ai-tooltip-box" role="tooltip">
                    <ul class="ai-tooltip-list">
                      <li>{{ __('Total Images') }}: {{ $aiImageTotalLabel }}</li>
                      <li>{{ __('Used Images') }}: {{ $aiUsedImages }}</li>
                    </ul>
                  </span>
                </span>
              </div>
              <span class="badge font-weight-bold" style="background: #0d6efd; color: #ffffff; border-radius: 20px; padding: 6px 16px; font-size: 12px;">
                {{ $aiUsedImages }} / {{ $aiImageTotalLabel }}
              </span>
            </li>
            @endif

            <!-- Categories check -->
            <li class="list-group-item border-0 p-3 shadow-sm d-flex justify-content-between align-items-center" style="border-radius: 12px; background: #ffffff; border: 1px solid rgba(0,0,0,0.06) !important;">
              <div class="d-flex align-items-center">
                <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 38px; height: 38px; border-radius: 10px; background: rgba(253, 126, 20, 0.1); color: #fd7e14; flex-shrink: 0;">
                  <i class="fas fa-folder" style="font-size: 16px;"></i>
                </span>
                <span class="font-weight-600" style="font-size: 14px; color: #333;">
                  @if ($totalCat > $catLimit) <i class="fas fa-exclamation-triangle text-danger mr-1"></i> @endif
                  {{ __('Categories Left') }} <i class="fas fa-info-circle text-muted ml-1" style="font-size: 12px; opacity: 0.7;"></i>
                  @if ($catLimit < 999999)
                    @if ($canAddCat == 0)
                      <span class="ml-2 font-weight-bold text-danger" style="font-size: 13px;">{{ __('Limit is over') }}</span>
                    @elseif($totalCat > $catLimit)
                      <span class="ml-2 font-weight-bold text-danger" style="font-size: 13px;">{{ __('Down Graded') }}</span>
                    @endif
                  @endif
                </span>
              </div>
              @if ($catLimit < 999999)
                <span class="badge font-weight-bold" style="background: {{ ($totalCat > $catLimit || $canAddCat == 0) ? '#fd7e14' : '#0d6efd' }}; color: #ffffff; border-radius: 20px; padding: 6px 16px; font-size: 12px;">
                  {{ $totalCat > $catLimit ? 0 : $canAddCat }}
                </span>
              @else
                <span class="badge font-weight-bold" style="background: rgba(40, 167, 69, 0.15); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3); border-radius: 20px; padding: 6px 16px; font-size: 12px;">
                  {{ __('Unlimited') }}
                </span>
              @endif
            </li>

            <!-- Items limit check -->
            <li class="list-group-item border-0 p-3 shadow-sm d-flex justify-content-between align-items-center" style="border-radius: 12px; background: #ffffff; border: 1px solid rgba(0,0,0,0.06) !important;">
              <div class="d-flex align-items-center">
                <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 38px; height: 38px; border-radius: 10px; background: rgba(13, 110, 253, 0.1); color: #0d6efd; flex-shrink: 0;">
                  <i class="fas fa-cube" style="font-size: 16px;"></i>
                </span>
                <span class="font-weight-600" style="font-size: 14px; color: #333;">
                  @if ($totalItem > $itemLimit) <i class="fas fa-exclamation-triangle text-danger mr-1"></i> @endif
                  {{ __('Items Left') }} <i class="fas fa-info-circle text-muted ml-1" style="font-size: 12px; opacity: 0.7;"></i>
                  @if ($itemLimit < 999999)
                    @if ($canAddItem == 0)
                      <span class="ml-2 font-weight-bold text-danger" style="font-size: 13px;">{{ __('Limit is over') }}</span>
                    @elseif($totalItem > $itemLimit)
                      <span class="ml-2 font-weight-bold text-danger" style="font-size: 13px;">{{ __('Down Graded') }}</span>
                    @endif
                  @endif
                </span>
              </div>
              @if ($itemLimit < 999999)
                <span class="badge font-weight-bold" style="background: {{ ($totalItem > $itemLimit || $canAddItem == 0) ? '#fd7e14' : '#0d6efd' }}; color: #ffffff; border-radius: 20px; padding: 6px 16px; font-size: 12px;">
                  {{ $totalItem > $itemLimit ? 0 : $canAddItem }}
                </span>
              @else
                <span class="badge font-weight-bold" style="background: rgba(40, 167, 69, 0.15); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3); border-radius: 20px; padding: 6px 16px; font-size: 12px;">
                  {{ __('Unlimited') }}
                </span>
              @endif
            </li>

            <!-- Orders limit check -->
            <li class="list-group-item border-0 p-3 shadow-sm d-flex justify-content-between align-items-center" style="border-radius: 12px; background: #ffffff; border: 1px solid rgba(0,0,0,0.06) !important;">
              <div class="d-flex align-items-center">
                <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 38px; height: 38px; border-radius: 10px; background: rgba(111, 66, 193, 0.1); color: #8b5cf6; flex-shrink: 0;">
                  <i class="fas fa-clipboard-list" style="font-size: 16px;"></i>
                </span>
                <span class="font-weight-600" style="font-size: 14px; color: #333;">
                  @if ($totalOrder > $orderLimit) <i class="fas fa-exclamation-triangle text-danger mr-1"></i> @endif
                  {{ __('Orders Left') }} <i class="fas fa-info-circle text-muted ml-1" style="font-size: 12px; opacity: 0.7;"></i>
                  @if ($orderLimit < 999999)
                    @if ($canAddOrder == 0)
                      <span class="ml-2 font-weight-bold text-danger" style="font-size: 13px;">{{ __('Limit is over') }}</span>
                    @elseif($totalOrder > $orderLimit)
                      <span class="ml-2 font-weight-bold text-danger" style="font-size: 13px;">{{ __('Down Graded') }}</span>
                    @endif
                  @endif
                </span>
              </div>
              @if ($orderLimit < 999999)
                <span class="badge font-weight-bold" style="background: {{ ($totalOrder > $orderLimit || $canAddOrder == 0) ? '#fd7e14' : '#0d6efd' }}; color: #ffffff; border-radius: 20px; padding: 6px 16px; font-size: 12px;">
                  {{ $totalOrder > $orderLimit ? 0 : $canAddOrder }}
                </span>
              @else
                <span class="badge font-weight-bold" style="background: rgba(40, 167, 69, 0.15); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3); border-radius: 20px; padding: 6px 16px; font-size: 12px;">
                  {{ __('Unlimited') }}
                </span>
              @endif
            </li>

            <!-- Blog post limit check -->
            @if (!is_null($package->post_limit))
              <li class="list-group-item border-0 p-3 shadow-sm d-flex justify-content-between align-items-center" style="border-radius: 12px; background: #ffffff; border: 1px solid rgba(0,0,0,0.06) !important;">
                <div class="d-flex align-items-center">
                  <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 38px; height: 38px; border-radius: 10px; background: rgba(40, 167, 69, 0.1); color: #10b981; flex-shrink: 0;">
                    <i class="fas fa-file-alt" style="font-size: 16px;"></i>
                  </span>
                  <span class="font-weight-600" style="font-size: 14px; color: #333;">
                    @if ($totalBlog > $blogLimit) <i class="fas fa-exclamation-triangle text-danger mr-1"></i> @endif
                    {{ __('Post/Blog Limit') }} <i class="fas fa-info-circle text-muted ml-1" style="font-size: 12px; opacity: 0.7;"></i>
                    @if ($blogLimit < 999999)
                      @if ($canAddBlog == 0)
                        <span class="ml-2 font-weight-bold text-danger" style="font-size: 13px;">{{ __('Limit is over') }}</span>
                      @elseif($totalBlog > $blogLimit)
                        <span class="ml-2 font-weight-bold text-danger" style="font-size: 13px;">{{ __('Down Graded') }}</span>
                      @endif
                    @endif
                  </span>
                </div>
                @if ($blogLimit < 999999)
                  <span class="badge font-weight-bold" style="background: {{ ($totalBlog > $blogLimit || $canAddBlog == 0) ? '#fd7e14' : '#0d6efd' }}; color: #ffffff; border-radius: 20px; padding: 6px 16px; font-size: 12px;">
                    {{ $totalBlog > $blogLimit ? 0 : $canAddBlog }}
                  </span>
                @else
                  <span class="badge font-weight-bold" style="background: rgba(40, 167, 69, 0.15); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3); border-radius: 20px; padding: 6px 16px; font-size: 12px;">
                    {{ __('Unlimited') }}
                  </span>
                @endif
              </li>
            @endif

            <!-- Coupon limit check -->
            @if (is_array($permissions) && in_array('Coupon code', $permissions))
              <li class="list-group-item border-0 p-3 shadow-sm d-flex justify-content-between align-items-center" style="border-radius: 12px; background: #ffffff; border: 1px solid rgba(0,0,0,0.06) !important;">
                <div class="d-flex align-items-center">
                  <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 38px; height: 38px; border-radius: 10px; background: rgba(139, 92, 246, 0.1); color: #8b5cf6; flex-shrink: 0;">
                    <i class="fas fa-ticket-alt" style="font-size: 16px;"></i>
                  </span>
                  <span class="font-weight-600" style="font-size: 14px; color: #333;">
                    @if ($totalCoupon > $couponLimit) <i class="fas fa-exclamation-triangle text-danger mr-1"></i> @endif
                    {{ __('Coupons Left') }} <i class="fas fa-info-circle text-muted ml-1" style="font-size: 12px; opacity: 0.7;"></i>
                    @if ($couponLimit < 999999)
                      @if ($canAddCoupon == 0)
                        <span class="ml-2 font-weight-bold text-danger" style="font-size: 13px;">{{ __('Limit is over') }}</span>
                      @elseif($totalCoupon > $couponLimit)
                        <span class="ml-2 font-weight-bold text-danger" style="font-size: 13px;">{{ __('Down Graded') }}</span>
                      @endif
                    @endif
                  </span>
                </div>
                @if ($couponLimit < 999999)
                  <span class="badge font-weight-bold" style="background: {{ ($totalCoupon > $couponLimit || $canAddCoupon == 0) ? '#fd7e14' : '#0d6efd' }}; color: #ffffff; border-radius: 20px; padding: 6px 16px; font-size: 12px;">
                    {{ $totalCoupon > $couponLimit ? 0 : $canAddCoupon }}
                  </span>
                @else
                  <span class="badge font-weight-bold" style="background: rgba(40, 167, 69, 0.15); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3); border-radius: 20px; padding: 6px 16px; font-size: 12px;">
                    {{ __('Unlimited') }}
                  </span>
                @endif
              </li>
            @endif

            <!-- Language limit check -->
            <li class="list-group-item border-0 p-3 shadow-sm d-flex justify-content-between align-items-center" style="border-radius: 12px; background: #ffffff; border: 1px solid rgba(0,0,0,0.06) !important;">
              <div class="d-flex align-items-center">
                <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 38px; height: 38px; border-radius: 10px; background: rgba(111, 66, 193, 0.1); color: #6f42c1; flex-shrink: 0;">
                  <i class="fas fa-globe" style="font-size: 16px;"></i>
                </span>
                <span class="font-weight-600" style="font-size: 14px; color: #333;">
                  @if ($totalLang > $langLimit) <i class="fas fa-exclamation-triangle text-danger mr-1"></i> @endif
                  {{ __('Additional Languages Left') }} <i class="fas fa-info-circle text-muted ml-1" style="font-size: 12px; opacity: 0.7;"></i>
                  @if ($langLimit < 999999)
                    @if ($canAddLang == 0)
                      <span class="ml-2 font-weight-bold text-danger" style="font-size: 13px;">{{ __('Limit is over') }}</span>
                    @elseif($totalLang > $langLimit)
                      <span class="ml-2 font-weight-bold text-danger" style="font-size: 13px;">{{ __('Down Graded') }}</span>
                    @endif
                  @endif
                </span>
              </div>
              @if ($langLimit < 999999)
                <span class="badge font-weight-bold" style="background: {{ ($totalLang > $langLimit || $canAddLang == 0) ? '#fd7e14' : '#0d6efd' }}; color: #ffffff; border-radius: 20px; padding: 6px 16px; font-size: 12px;">
                  {{ $totalLang > $langLimit ? 0 : $canAddLang }}
                </span>
              @else
                <span class="badge font-weight-bold" style="background: rgba(40, 167, 69, 0.15); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3); border-radius: 20px; padding: 6px 16px; font-size: 12px;">
                  {{ __('Unlimited') }}
                </span>
              @endif
            </li>

            <!-- Custom page limit check -->
            @if (!is_null($package->number_of_custom_page))
              <li class="list-group-item border-0 p-3 shadow-sm d-flex justify-content-between align-items-center" style="border-radius: 12px; background: #ffffff; border: 1px solid rgba(0,0,0,0.06) !important;">
                <div class="d-flex align-items-center">
                  <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 38px; height: 38px; border-radius: 10px; background: rgba(13, 110, 253, 0.1); color: #0d6efd; flex-shrink: 0;">
                    <i class="fas fa-file" style="font-size: 16px;"></i>
                  </span>
                  <span class="font-weight-600" style="font-size: 14px; color: #333;">
                    @if ($totalCustomPage > $pageLimit) <i class="fas fa-exclamation-triangle text-danger mr-1"></i> @endif
                    {{ __('Additional Page Left') }} <i class="fas fa-info-circle text-muted ml-1" style="font-size: 12px; opacity: 0.7;"></i>
                    @if ($pageLimit < 999999)
                      @if ($canAddPage == 0)
                        <span class="ml-2 font-weight-bold text-danger" style="font-size: 13px;">{{ __('Limit is over') }}</span>
                      @elseif($totalCustomPage > $pageLimit)
                        <span class="ml-2 font-weight-bold text-danger" style="font-size: 13px;">{{ __('Down Graded') }}</span>
                      @endif
                    @endif
                  </span>
                </div>
                @if ($pageLimit < 999999)
                  <span class="badge font-weight-bold" style="background: {{ ($totalCustomPage > $pageLimit || $canAddPage == 0) ? '#fd7e14' : '#0d6efd' }}; color: #ffffff; border-radius: 20px; padding: 6px 16px; font-size: 12px;">
                    {{ $totalCustomPage > $pageLimit ? 0 : $canAddPage }}
                  </span>
                @else
                  <span class="badge font-weight-bold" style="background: rgba(40, 167, 69, 0.15); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3); border-radius: 20px; padding: 6px 16px; font-size: 12px;">
                    {{ __('Unlimited') }}
                  </span>
                @endif
              </li>
            @endif

            <!-- Other features -->
            @if ($permissions != null)
              @php
                $featureIcons = [
                  'custom_domain' => ['icon' => 'fa-globe', 'color' => '#28a745', 'bg' => 'rgba(40, 167, 69, 0.1)'],
                  'subdomain' => ['icon' => 'fa-link', 'color' => '#28a745', 'bg' => 'rgba(40, 167, 69, 0.1)'],
                  'qr_builder' => ['icon' => 'fa-qrcode', 'color' => '#28a745', 'bg' => 'rgba(40, 167, 69, 0.1)'],
                  'blog' => ['icon' => 'fa-blog', 'color' => '#28a745', 'bg' => 'rgba(40, 167, 69, 0.1)'],
                  'custom_page' => ['icon' => 'fa-file-alt', 'color' => '#28a745', 'bg' => 'rgba(40, 167, 69, 0.1)'],
                  'google_login' => ['icon' => 'fa-google', 'color' => '#28a745', 'bg' => 'rgba(40, 167, 69, 0.1)'],
                  'google_analytics' => ['icon' => 'fa-chart-line', 'color' => '#28a745', 'bg' => 'rgba(40, 167, 69, 0.1)'],
                  'ai_content_and_image_generator' => ['icon' => 'fa-magic', 'color' => '#28a745', 'bg' => 'rgba(40, 167, 69, 0.1)'],
                ];
              @endphp
              @foreach ($permissions as $featureKey)
                @php
                  $cleanKey = strtolower(trim($featureKey));
                  if ($cleanKey === 'ai_content_and_image_generator' || $cleanKey === 'ai content and image generator' || str_contains($cleanKey, 'ai_content')) {
                      continue;
                  }
                  if (in_array($cleanKey, ['disqus', 'bank transfer integrations', 'facebook pixel', 'coupon code', 'coupon_code'])) {
                      continue;
                  }
                  $iconData = $featureIcons[$cleanKey] ?? ['icon' => 'fa-check-circle', 'color' => '#28a745', 'bg' => 'rgba(40, 167, 69, 0.1)'];
                  $featureName = str_replace('_', ' ', $featureKey);
                @endphp
                <li class="list-group-item border-0 p-3 shadow-sm d-flex justify-content-between align-items-center" style="border-radius: 12px; background: #ffffff; border: 1px solid rgba(0,0,0,0.06) !important;">
                  <div class="d-flex align-items-center">
                    <span class="d-inline-flex align-items-center justify-content-center mr-3" style="width: 38px; height: 38px; border-radius: 10px; background: {{ $iconData['bg'] }}; color: {{ $iconData['color'] }}; flex-shrink: 0;">
                      <i class="fas {{ $iconData['icon'] }}" style="font-size: 16px;"></i>
                    </span>
                    <span class="font-weight-600" style="font-size: 14px; color: #333;">
                      {{ __($featureName) }} <i class="fas fa-info-circle text-muted ml-1" style="font-size: 12px; opacity: 0.7;"></i>
                    </span>
                  </div>
                  <span class="badge font-weight-bold" style="background: rgba(40, 167, 69, 0.15); color: #28a745; border: 1px solid rgba(40, 167, 69, 0.3); border-radius: 20px; padding: 6px 16px; font-size: 12px;">
                    {{ __('Enabled') }}
                  </span>
                </li>
              @endforeach
            @endif
          </ul>
        </div>
        <div class="modal-footer border-top py-3 px-4 bg-transparent d-flex align-items-center justify-content-between flex-wrap gap-2">
          <div class="p-3 d-flex align-items-center text-left mb-2 mb-sm-0" style="background: rgba(13, 110, 253, 0.08); border-radius: 10px; color: #0d6efd; font-size: 13px; flex-grow: 1; margin-right: 15px;">
            <i class="fas fa-info-circle mr-2" style="font-size: 16px; flex-shrink: 0;"></i>
            <span>{{ __('Limits are updated in real-time. Contact support for any queries.') }}</span>
          </div>
          <button type="button" class="btn btn-primary font-weight-bold px-4 py-2 shadow-sm" data-dismiss="modal" style="border-radius: 8px; font-size: 14px; background: #0d6efd; border-color: #0d6efd;">
            {{ __('Close') }}
          </button>
        </div>
      </div>
    </div>
  </div>
@endif

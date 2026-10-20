<div class="sidebar hide-scroll">

    <div class="sidebar-header d-flex align-items-center px-3 position-relative">
        <div class="d-flex align-items-center overflow-hidden" style="max-height: 56px;">
            <a href="{{ session('login_as') == "admin" ? route("admin.dashboard") : route("app.dashboard") }}" class="d-flex align-items-center text-decoration-none">
                <div class="logo-small">
                    <img src="{{ asset('img/whatsappbulklogo.png') }}" class="max-w-180" style="max-height: 46px; width: auto; object-fit: contain;" alt="Whatsappbulk" onerror="this.src='/img/whatsappbulklogo.png'">
                </div>
                <div class="logo-large">
                    <img src="{{ asset('img/whatsappbulklogo.png') }}" class="max-w-180" style="max-height: 52px; width: auto; object-fit: contain;" alt="Whatsappbulk" onerror="this.src='/img/whatsappbulklogo.png'">
                </div>
            </a>
        </div>

        <div class="sidebar-toggle bg-light">
            <a href="javascript:void(0)" class="d-flex align-items-center justify-content-center border-1 px-2 rounded size-24 fs-14 d-block text-gray-500"><i class="fa-duotone fa-arrow-right-to-line"></i></a>
        </div>
    </div>

    @php
        $waUser = auth()->user() ?? (\Modules\Auth\Models\Auth::user() ?? null);
        $waIsLocked = false;
        try {
            if ($waUser && !empty($waUser->expiration_date) && $waUser->expiration_date != -1) {
                $expTimestamp = is_numeric($waUser->expiration_date) ? (int)$waUser->expiration_date : strtotime($waUser->expiration_date);
                if ($expTimestamp > 0 && (time() - $expTimestamp) > 86400) {
                    $waIsLocked = true;
                }
            }
        } catch (\Throwable $e) {}

        $isPayUri = function($uri) {
            $u = strtolower((string)$uri);
            return strpos($u, 'plan') !== false
                || strpos($u, 'pricing') !== false
                || strpos($u, 'subscription') !== false
                || strpos($u, 'payment') !== false
                || strpos($u, 'profile/plan') !== false
                || strpos($u, 'logout') !== false;
        };
    @endphp

    <style>
    .wa-nav-locked {
        pointer-events: none !important;
        opacity: 0.42 !important;
        cursor: not-allowed !important;
        filter: grayscale(0.6);
    }
    .wa-lock-badge {
        background: #ef4444;
        color: #fff;
        font-size: 9px;
        font-weight: 700;
        padding: 2px 5px;
        border-radius: 4px;
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }
    </style>

    <div class="menu d-flex flex-column align-items-lg-center flex-row-auto" id="accordionMenu">

        @if($waIsLocked)
            <div class="px-3 py-2 my-2 mx-3 w-100" style="background: rgba(239, 68, 68, 0.12); border: 1.5px solid #ef4444; border-radius: 10px; text-align: center; max-width: calc(100% - 24px);">
                <div class="text-danger fw-bold fs-12 d-flex align-items-center justify-content-center">
                    <i class="fa-solid fa-lock me-1"></i> {{ __('Navigation Locked') }}
                </div>
                <div class="text-muted fs-11 mt-1 mb-2">
                    {{ __('Plan expired > 24h. Please renew your plan.') }}
                </div>
                <a href="{{ url('/app/profile/plan') }}" class="btn btn-sm btn-danger wp-100 fw-bold py-1 fs-11 text-white text-decoration-none" style="border-radius: 6px; box-shadow: 0 2px 8px rgba(239,68,68,0.4);">
                    <i class="fa-solid fa-bolt me-1"></i> {{ __('Renew Plan Now') }}
                </a>
            </div>
        @endif
        
        @if( isset( $sidebar['top'] ) )
            <div class="menu-top d-flex flex-column flex-column-fluid w-100 mb-2">

                @php
                    $current_tab = 0;
                @endphp

                @foreach( $sidebar['top'] as $key => $value )

                    @if( $current_tab == 0 && isset( $value['tab_name'] ) )
                        <div class="menu-item pt-2">
                            <div class="menu-heading"><span>{{ __($value['tab_name']) }}</span></div>
                        </div>
                    @endif
                
                    @if($value['tab_id'] != $current_tab && $current_tab)
                        <div class="menu-item h-1 bg-gray-200 my-2"> </div>

                        @if( isset( $value['tab_name'] ) )
                        <div class="menu-item pt-2">
                            <div class="menu-heading"><span>{{ __($value['tab_name']) }}</span></div>
                        </div>
                        @endif
                    @endif

                    @php 
                        $current_tab = $value['tab_id'];
                    @endphp

                    @if(!isset($value['sub_menu']))
                        @php
                            $isItemLocked = $waIsLocked && !$isPayUri($value['uri'] ?? '');
                        @endphp
                        <div class="menu-item">
                            <a class="menu-link flex-grow-1 align-items-center {{ $isItemLocked ? 'wa-nav-locked' : '' }} {{ menu_active($value['uri']) ? 'text-primary' : '' }}" href="{{ $isItemLocked ? 'javascript:void(0)' : url( $value['uri'] ) }}">
                                <div class="menu-icon fs-18 {{ menu_active($value['uri']) ? 'text-primary' : 'text-gray-900' }}">
                                    <i class="{{ $value['icon'] }}" {!! Core::sidebarColor() !!}></i>
                                </div>
                                <div class="menu-title d-flex align-items-center flex-grow-1 fs-14 fw-5 text-truncate" {!! menu_active($value['uri']) ? Core::sidebarColor() : '' !!}>
                                    <div class="text-truncate">
                                        {{ __($value['name']) }}
                                    </div>
                                    @if($isItemLocked)
                                        <i class="fa-solid fa-lock text-danger ms-auto fs-12"></i>
                                    @endif
                                </div>
                            </a>
                        </div>

                        @if(($value['uri'] ?? '') == 'admin/plans')
                            <div class="menu-item">
                                <a class="menu-link flex-grow-1 align-items-center {{ request()->is('admin/expire-alert*') ? 'text-primary' : '' }}" href="{{ url('admin/expire-alert') }}">
                                    <div class="menu-icon fs-18 {{ request()->is('admin/expire-alert*') ? 'text-primary' : 'text-gray-900' }}">
                                        <i class="fa-light fa-bell-exclamation text-warning"></i>
                                    </div>
                                    <div class="menu-title d-flex align-items-center flex-grow-1 fs-14 fw-5 text-truncate">
                                        <div class="text-truncate">
                                            {{ __('Expire Alert') }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endif
                    @else
                        @php
                            $sub_menus = $value['sub_menu'];
                            $is_sub_active = false;
                            foreach($sub_menus as $sub_menu) {
                                if(menu_active($sub_menu['uri'])) {
                                    $is_sub_active = true;
                                    break;
                                }
                            }
                            $isAccordionLocked = $waIsLocked;
                        @endphp
                        <div class="menu-item">
                            <div class="menu-link flex-grow-1 align-items-center collapsed {{ $isAccordionLocked ? 'wa-nav-locked' : '' }} {{ $is_sub_active ? 'text-primary' : '' }}" {{ $isAccordionLocked ? '' : 'data-bs-toggle="collapse" data-bs-target="#menu-' . $key . '"' }}>
                                <div class="menu-icon fs-18 {{ $is_sub_active ? 'text-primary' : 'text-gray-900' }}">
                                    <i class="{{ $value["icon"] }}" {!! Core::sidebarColor() !!}></i>
                                </div>
                                <div class="menu-title d-flex align-items-center flex-grow-1 fs-14 fw-5" {!! $is_sub_active ? Core::sidebarColor() : '' !!}>
                                    {{ __($value["name"]) }}
                                    @if($isAccordionLocked)
                                        <i class="fa-solid fa-lock text-danger ms-auto fs-12"></i>
                                    @endif
                                </div>
                                @if(!$isAccordionLocked)
                                <div class="menu-arrow">
                                    <i class="fa-duotone fa-plus menu-item-show"></i>
                                    <i class="fa-duotone fa-minus menu-item-hide"></i>
                                </div>
                                @endif
                            </div>

                            @if(!$isAccordionLocked)
                            <div  id="menu-{{ $key }}" class="menu-accordion accordion-collapse collapse {{ $is_sub_active ? 'show' : '' }}" data-bs-parent="#accordionMenu">
                                @foreach($sub_menus as $sub_menu)
                                <div class="menu-item">
                                    <a class="menu-link {{ menu_active($sub_menu['uri']) ? 'active text-primary' : '' }}" href="{{ url($sub_menu['uri']) }}">
                                        <span class="menu-bullet"></span>
                                        <span class="menu-title d-flex align-items-center flex-grow-1 fs-13">{{ __($sub_menu['name']) }}</span>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>

                    @endif

                @endforeach

            </div>
        @endif

        @if( isset( $sidebar['bottom'] ) )
            <div class="menu-bottom d-flex flex-column flex-column-fluid w-100 mt-auto">

                @foreach( $sidebar['bottom'] as $value )
                    @php
                        $isBottomLocked = $waIsLocked && !$isPayUri($value['uri'] ?? '');
                    @endphp
                    <div class="menu-item">
                        <a href="{{ $isBottomLocked ? 'javascript:void(0)' : url( $value['uri'] ) }}" class="menu-link flex-grow-1 align-items-center {{ $isBottomLocked ? 'wa-nav-locked' : '' }}">
                            <div class="menu-icon text-gray-600 fs-18">
                                <i class="{{ $value['icon'] }}" {!! Core::sidebarColor() !!}></i>
                            </div>
                            <div class="menu-title d-flex align-items-center flex-grow-1 fs-14 fw-5" {!! menu_active($value['uri']) ? Core::sidebarColor() : '' !!}>
                                {{ __($value['name']) }}
                                @if($isBottomLocked)
                                    <i class="fa-solid fa-lock text-danger ms-auto fs-12"></i>
                                @endif
                            </div>
                        </a>
                    </div>

                @endforeach
     
                @foreach(\Core::getSidebarBlocks() as $sidebarItem)
                    <div class="sidebar-blocks">
                        @php
                            $isVisible = $sidebarItem['visible'] ?? fn() => true;
                        @endphp
                        @if($isVisible())
                            {!! is_callable($sidebarItem['item']) ? $sidebarItem['item']() : $sidebarItem['item'] !!}
                        @endif
                    </div>
                @endforeach
            </div>

        @endif
    </div>

</div>
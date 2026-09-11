@php
  $currentDbName = config('database.connections.mysql.database');
  $dbConnectionStatus = 'Connected';
  $dbStatusColor = '#4ade80';

  try {
      \Illuminate\Support\Facades\DB::connection('mysql')->getPdo();
  } catch (\Throwable $e) {
      $dbConnectionStatus = 'Error: ' . $e->getMessage();
      $dbStatusColor = '#f87171';
  }

  $currentHost = request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? 'Unknown');
  
  $subdomainSlug = null;
  $wbClient = null;

  try {
      $pathSegment = request()->segment(1);
      $hostParts = explode('.', $currentHost);
      
      $reservedHosts = ['www', 'checkout', 'admin', 'localhost', 'websitebuilder', 'website-builder', 'launchshop'];
      if (count($hostParts) > 2 && !in_array(strtolower($hostParts[0]), $reservedHosts)) {
          $subdomainSlug = $hostParts[0];
      } elseif ($pathSegment && !in_array(strtolower($pathSegment), ['admin', 'checkout', 'membership', 'login', 'register', 'assets', 'api', 'pricing', 'templates'])) {
          $subdomainSlug = $pathSegment;
      }

      if (\Illuminate\Support\Facades\Schema::hasTable('wb_customers')) {
          if ($subdomainSlug) {
              $wbClient = \App\Models\WebsiteBuilder\WbCustomer::where('subdomain', $subdomainSlug)->first();
          }
          if (!$wbClient && session('wb_customer_id')) {
              $wbClient = \App\Models\WebsiteBuilder\WbCustomer::find(session('wb_customer_id'));
          }
      }

      if (!$wbClient && \Illuminate\Support\Facades\Schema::hasTable('users')) {
          $lsUser = null;
          if ($subdomainSlug) {
              $lsUser = \App\Models\User::where('username', $subdomainSlug)->first();
          }
          if (!$lsUser && session('new_user_username')) {
              $lsUser = \App\Models\User::where('username', session('new_user_username'))->first();
          }
          if ($lsUser) {
              $wbClient = (object)[
                  'company_name' => $lsUser->shop_name ?: ($lsUser->first_name ?: $lsUser->username),
                  'username' => $lsUser->username,
                  'email' => $lsUser->email,
              ];
          }
      }
  } catch (\Throwable $e) {}

  $resolvedUser = null;
  try {
      $resolvedUser = function_exists('getUser') ? getUser() : null;
  } catch (\Throwable $e) {}

  $userLabel = $resolvedUser ? ($resolvedUser->username ?? $resolvedUser->email ?? 'Resolved') : 'None';
@endphp

<div id="launchshop-debug-status-bar" style="position: fixed; bottom: 12px; left: 12px; z-index: 9999999; background: rgba(15, 23, 42, 0.95); color: #f8fafc; padding: 8px 16px; border-radius: 8px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, monospace; font-size: 12px; line-height: 1.4; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5); border: 1px solid rgba(255, 255, 255, 0.2); display: flex; align-items: center; gap: 14px; pointer-events: auto; backdrop-filter: blur(8px);">
  <div style="display: flex; align-items: center; gap: 6px;">
    <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background-color: {{ $dbStatusColor }}; box-shadow: 0 0 8px {{ $dbStatusColor }};"></span>
    <strong style="color: #94a3b8; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px;">Active DB:</strong>
    <span style="color: #60a5fa; font-weight: 600;">{{ $currentDbName }}</span>
  </div>
  <div style="width: 1px; height: 14px; background: rgba(255,255,255,0.2);"></div>
  <div>
    <strong style="color: #94a3b8; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px;">Host:</strong>
    <span style="color: #cbd5e1;">{{ $currentHost }}{{ $subdomainSlug ? ' (' . $subdomainSlug . ')' : '' }}</span>
  </div>
  <div style="width: 1px; height: 14px; background: rgba(255,255,255,0.2);"></div>
  <div>
    <strong style="color: #94a3b8; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px;">DB Status:</strong>
    <span style="color: {{ $dbStatusColor }}; font-weight: 600;">{{ $dbConnectionStatus }}</span>
  </div>
  <div style="width: 1px; height: 14px; background: rgba(255,255,255,0.2);"></div>
  <div>
    <strong style="color: #94a3b8; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px;">Client:</strong>
    @if($wbClient)
      <span id="debug-client-span" style="color: #4ade80; font-weight: 600;">{{ $wbClient->company_name ?? $wbClient->username }} ({{ $wbClient->email }})</span>
    @elseif($subdomainSlug)
      <span id="debug-client-span" style="color: #fbbf24; font-weight: 500;">No Client Record for '{{ $subdomainSlug }}'</span>
    @else
      <span id="debug-client-span" style="color: #a7f3d0; font-weight: 500;">{{ $userLabel }}</span>
    @endif
  </div>
</div>

<script>
  window.syncLaunchshopClient = function() {
    try {
      var rawPending = localStorage.getItem('ls_pending_checkout_user');
      var clientSpan = document.getElementById('debug-client-span');
      
      console.group('%c[LaunchShop Store Sync Debugger]', 'color: #3b82f6; font-weight: bold; font-size: 13px;');
      console.log('Subdomain/Path Slug:', '{{ $subdomainSlug }}');
      console.log('Current Host:', '{{ $currentHost }}');
      console.log('Pending LocalStorage Data:', rawPending ? JSON.parse(rawPending) : 'None');

      if (!rawPending) {
        console.log('No pending client checkout found in LocalStorage.');
        console.groupEnd();
        return;
      }

      var pending = JSON.parse(rawPending);
      if (pending && (pending.username || pending.email || pending.shop_name)) {
        if (clientSpan) {
          clientSpan.style.color = '#38bdf8';
          clientSpan.style.fontWeight = '600';
          clientSpan.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Syncing Client Data to Database...';
        }

        var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        fetch('/checkout/launchshop-client-sync', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify(pending)
        }).then(function(res) {
          console.log('HTTP Status Code:', res.status, res.statusText);
          return res.json();
        }).then(function(data) {
          console.log('Server Sync Response:', data);
          if (data && data.success) {
            console.log('%c[SUCCESS] Customer registered/synced cleanly in DB!', 'color: #22c55e; font-weight: bold;', data.username);
            localStorage.removeItem('ls_pending_checkout_user');
            if (clientSpan) {
              clientSpan.style.color = '#4ade80';
              clientSpan.style.fontWeight = '600';
              clientSpan.innerHTML = (pending.shop_name || data.username) + ' (' + (pending.email || 'synced') + ')';
            }
          } else {
            var errMsg = (data && data.error) ? data.error : ((data && data.message) ? data.message : 'Unknown Server Error');
            console.warn('[Sync Warning]', errMsg);
            if (clientSpan) {
              clientSpan.style.color = '#ef4444';
              clientSpan.style.fontWeight = '500';
              clientSpan.innerHTML = 'Sync Failed: ' + errMsg + ' <button onclick="syncLaunchshopClient()" style="background:#ef4444;color:#fff;border:none;border-radius:4px;padding:2px 8px;cursor:pointer;font-size:10px;margin-left:4px;">Retry</button>';
            }
          }
          console.groupEnd();
        }).catch(function(err) {
          console.error('[LaunchShop Auto-Sync Exception]', err);
          if (clientSpan) {
            clientSpan.style.color = '#ef4444';
            clientSpan.style.fontWeight = '500';
            clientSpan.innerHTML = 'Sync Error: ' + (err.message || 'Connection Error') + ' <button onclick="syncLaunchshopClient()" style="background:#ef4444;color:#fff;border:none;border-radius:4px;padding:2px 8px;cursor:pointer;font-size:10px;margin-left:4px;">Retry</button>';
          }
          console.groupEnd();
        });
      } else {
        console.groupEnd();
      }
    } catch(e) {
      console.error('[LaunchShop Debugger Exception]', e);
    }
  };

  (function() {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', window.syncLaunchshopClient);
    } else {
      window.syncLaunchshopClient();
    }
  })();
</script>

@php
  $currentDbName = config('database.connections.mysql.database');
  $dbConnectionStatus = 'Connected';
  $dbStatusColor = '#4ade80';

  try {
      \Illuminate\Support\Facades\DB::connection('mysql')->getPdo();
  } catch (\Throwable $e) {
      $dbConnectionStatus = 'Access Denied / Connection Error: ' . $e->getMessage();
      $dbStatusColor = '#f87171';
  }

  $currentHost = request()->getHost() ?: ($_SERVER['HTTP_HOST'] ?? 'Unknown');
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
    <span style="color: #cbd5e1;">{{ $currentHost }}</span>
  </div>
  <div style="width: 1px; height: 14px; background: rgba(255,255,255,0.2);"></div>
  <div>
    <strong style="color: #94a3b8; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px;">DB Status:</strong>
    <span style="color: {{ $dbStatusColor }}; font-weight: 600;">{{ $dbConnectionStatus }}</span>
  </div>
  <div style="width: 1px; height: 14px; background: rgba(255,255,255,0.2);"></div>
  <div>
    <strong style="color: #94a3b8; text-transform: uppercase; font-size: 10px; letter-spacing: 0.5px;">Tenant:</strong>
    <span style="color: #a7f3d0; font-weight: 500;">{{ $userLabel }}</span>
  </div>
</div>
